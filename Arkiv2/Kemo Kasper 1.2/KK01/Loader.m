//
//  Loader.m
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 7/23/13.
//  Copyright 2013 UOVO. All rights reserved.
//

#import "Loader.h"

@implementation Loader

- (void)onEnterTransitionDidFinish
{
    [super onEnterTransitionDidFinish];
    
    //PRELOAD BOOK
    if(_mode == 0) {
        bookScene = [CCBReader sceneWithNodeGraphFromFile:@"Book.ccbi"];
        [self scheduleOnce:@selector(openBook) delay:0];
    }
    //PRELOAD GAME
    else if(_mode > 0) {
        [[Game sharedGame] initialize];
        [[Animations sharedAnimations] prepareGame];
        [[SoundEffects sharedSoundEffects] initializeGameSounds];
        GameLayer *gameLayer = (GameLayer*)[CCBReader nodeGraphFromFile:@"Game.ccbi"];
        gameLayer.patient = _mode;
        [Game sharedGame].currentPatient = _mode;
        gameScene = [CCScene node];
        [gameScene addChild:gameLayer];
        [self scheduleOnce:@selector(startGame) delay:0];
    }
}

- (void)openBook
{
    [[CCDirector sharedDirector] replaceScene:[CCTransitionFade transitionWithDuration:1.0
                                                                                 scene:bookScene
                                                                             withColor:ccBLACK]];
}

- (void)startGame
{
    [[CCDirector sharedDirector] replaceScene:[CCTransitionFade transitionWithDuration:1.0
                                                                                 scene:gameScene
                                                                             withColor:ccBLACK]];
}

@end
