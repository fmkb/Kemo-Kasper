//
//  ShockWave.m
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 7/16/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import "ShockWave.h"

@implementation ShockWave

- (id)initAtPosition:(CGPoint)initialPosition
{
    self = [super initWithSpriteFrameName:@"shock/0000"];
    if(self) {
        self.position = initialPosition;
    }
    return self;
}

- (void)onEnter
{
    [super onEnter];
    
    CCAnimation *animation = [CCAnimation animationWithSpriteFrames:[Animations sharedAnimations].shockWaveAnimationFrames delay:0.02f];
    CCSequence *sequence = [CCSequence actions:[CCAnimate actionWithAnimation:animation],
                            [CCCallFunc actionWithTarget:self selector:@selector(remove)], nil];
    [self runAction:sequence];
}

- (void)remove
{
    [self removeFromParentAndCleanup:YES];
}

@end
