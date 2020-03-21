//
//  SuccessLayer.m
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 8/26/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import "SuccessLayer.h"

@implementation SuccessLayer

- (void)didLoadFromCCB
{
    round.string = [@"RUNDE " stringByAppendingFormat:@"%d", [Game sharedGame].currentRound];
}

- (void)onEnter
{
    [super onEnter];
    [[SoundEffects sharedSoundEffects] roundComplete];
}

@end
