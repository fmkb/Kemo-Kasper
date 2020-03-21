//
//  FinalLayer.m
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 8/27/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import "FinalLayer.h"

#import "Appirater.h"

@implementation FinalLayer

- (void)didLoadFromCCB
{
    loader = [CCBReader sceneWithNodeGraphFromFile:@"Loading.ccbi"];
    layer = [loader.children objectAtIndex:0];
    
    switch ([Game sharedGame].currentPatient) {
        case 1:
            patient1.visible = YES;
            finalText.string = @"Nu kan vi ikke finde flere sure kræftceller i drengen.";
            break;
        case 2:
            patient2.visible = YES;
            finalText.string = @"Nu kan vi ikke finde flere sure kræftceller i pigen.";
            break;
        case 3:
            patient3.visible = YES;
            finalText.string = @"Nu kan vi ikke finde flere sure kræftceller i drengen.";
            break;
        case 4:
            patient4.visible = YES;
            finalText.string = @"Nu kan vi ikke finde flere sure kræftceller i pigen.";
            break;
        default:
            break;
    }
}

- (void)gotoBook
{
    [[SoundEffects sharedSoundEffects] click];
    layer.mode = 0;
	[[CCDirector sharedDirector] replaceScene:[CCTransitionFade transitionWithDuration:1.0
                                                                                 scene:loader
                                                                             withColor:ccBLACK]];
}

- (void)gotoMenu
{
    [Appirater userDidSignificantEvent:YES];
    [[SoundEffects sharedSoundEffects] click];
    [[CCDirector sharedDirector] popScene];
}

@end
