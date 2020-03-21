//
//  HUDLayer.m
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 6/24/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import "HUDLayer.h"

#import "GameLayer.h"

@implementation HUDLayer

- (void)didLoadFromCCB
{
    self.zOrder = 3;
    winSize = [[CCDirector sharedDirector] winSize];
    
    _kkSign = [[KemoKasperSign alloc] init];
    [self addChild:_kkSign z:3 tag:0];
    _kkDialog = (CCLayer*)[CCBReader nodeGraphFromFile:@"kemokasper_dialog.ccbi" owner:self];
    _kkDialogCounter = 0;
}

- (void)startLayer
{
    counter = [Game sharedGame].roundDuration;
    roundNumber.string = [NSString stringWithFormat:@"%d",[Game sharedGame].currentRound+1];
    
    [self updateCounterDisplay];
    [self updateCancerBar];
    
    [self schedule:@selector(second) interval:1];
    if([Game sharedGame].currentRound >= 1) {
        [self scheduleOnce:@selector(showKemoKasperSign) delay:[Game sharedGame].kemoKasperShowTime];
    }
}

- (void)showDialog
{
    [(GameLayer*)self.parent onExit];
    [[[CCDirector sharedDirector] runningScene] addChild:_kkDialog z:4];
}

- (void)closeDialog
{
    [_kkDialog removeFromParentAndCleanup:YES];
    [(GameLayer*)self.parent onEnter];
}

- (void)callKemoKasper
{
    [_kkSign removeFromParentAndCleanup:YES];
    [[SoundEffects sharedSoundEffects] kasperSignPressed];
    [self closeDialog];
    [[Game sharedGame].kemoKasper inject];
}

#pragma mark Time management

- (void)second
{
    if(counter == 0) {
        [self finishedRound];
    } else {
        counter--;
        [Game sharedGame].kemoKasperLastInnocentKillCounter++;
        if([Game sharedGame].currentRound > 1 && [Game sharedGame].patient.currentFrame >= 8 && [Game sharedGame].kemoKasperLastInnocentKillCounter == 2) {
            [[Game sharedGame].patient gotoFrame:11];
        }
        if(counter%5 == 0) {
            if([Game sharedGame].currentRound == 0) {
                [[Game sharedGame].patient nextFrame];
            }
        }
        if([Game sharedGame].currentRound > 0 && ![Game sharedGame].usedKemoKasper && counter == 18 && _kkDialogCounter < 20) {
            [self showDialog];
        }
        float scaleX = (float)counter / (float)[Game sharedGame].roundDuration;
        id scale = [CCScaleTo actionWithDuration:1 scaleX:scaleX scaleY:1];
        [remainingTime runAction:scale];
        if(counter <= 5) {
            [[SoundEffects sharedSoundEffects] timerWarning];
        }
        [self updateCounterDisplay];
    }
}

- (void)updateCounterDisplay
{
    int seconds = counter % 60;
    [counterLabel setString:[NSString stringWithFormat:@"%02d",seconds]];
}

- (void)updateCellsDisplay
{
    [self updateCancerBar];
    //
    if([[Game sharedGame] cancerDefeated]) {
        [self finishedGame];
    }
}

- (void)updateCancerBar
{
    float scaleX = (float)([Game sharedGame].cancerCellsNumber-[[Game sharedGame] cancerCellsKilled]) / (float)[Game sharedGame].cancerCellsNumber;
    if(1-scaleX >= 0 && 1-scaleX <= 1)
        gainedHealth.scaleX = 1-scaleX;
    if(scaleX >= 0 && scaleX <= 1)
        remainingCancer.scaleX = scaleX;
}

- (int)getCounter
{
    return counter;
}

#pragma mark Patient

- (void)addPatient:(int)patientNo
{
    [Game sharedGame].patient = [[Patient alloc] initPatient:[Game sharedGame].currentPatient];
    [Game sharedGame].patient.anchorPoint = ccp(0,0);
    if(UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPhone) {
        [Game sharedGame].patient.position = ccp(0,258);
    } else {
        [Game sharedGame].patient.position = ccp(0,winSize.height-[Game sharedGame].patient.contentSize.height*[Game sharedGame].patient.scale);
    }
    int frame = ([Game sharedGame].currentPatientFrame >= 8) ? 11 : [Game sharedGame].currentPatientFrame;
    [[Game sharedGame].patient gotoFrame:frame];
    [self addChild:[Game sharedGame].patient];
}

#pragma mark Game options

- (void)timeBonus:(int)seconds
{
    if(counter > 1) {
        [self unschedule:@selector(second)];
        [remainingTime stopAllActions];
        counter += seconds;
        float scaleX = (float)counter / (float)[Game sharedGame].roundDuration;
        id scale = [CCScaleTo actionWithDuration:.5 scaleX:scaleX scaleY:1];
        id easing = [CCEaseElasticOut actionWithAction:scale];
        [remainingTime runAction:easing];
        [self updateCounterDisplay];
        [self schedule:@selector(second) interval:1];
    }
}

- (void)showKemoKasperSign
{
    [_kkSign slide:YES];
}

- (void)pause
{
    if(self.touchEnabled)
        [(GameLayer*)self.parent pauseGame];
}

- (void)finishedRound
{
    [(GameLayer*)self.parent finishedRound];
}

- (void)finishedGame
{
    [(GameLayer*)self.parent finishedGame];
}

@end
