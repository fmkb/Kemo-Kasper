//
//  GameOverLayer.m
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 8/26/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import "GameOverLayer.h"

@implementation GameOverLayer

- (void)didLoadFromCCB
{
    [self setOpacity:0];
}

- (void)onEnter
{
    [super onEnter];
    [[SoundEffects sharedSoundEffects] roundComplete];
    id fade = [CCFadeIn actionWithDuration:.5];
    [self runAction:fade];
}

@end
