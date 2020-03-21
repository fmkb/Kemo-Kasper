//
//  Boom.m
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 6/27/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import "Boom.h"

@implementation Boom

- (id)initAt:(CGPoint)point
   withColor:(UIColor*)color
       withN:(int)n
{
    self = [super init];
    if(self) {
        _position = point;
        _boomBits = [CCArray array];
        for (int i = 0; i < n; i++) {
            int randomSize = arc4random() % 10 + 5;
            BoomBit *bit = [[BoomBit alloc] initAt:point withSize:randomSize andColor:color];
            [_boomBits addObject:bit];
        }
    }
    return self;
}

- (void)onEnter
{
    [super onEnter];
    [self explode];
}

- (void)explode
{
    for (BoomBit *bit in _boomBits) {
        if(!bit.parent)
            [self addChild:bit];
        [bit explode];
    }
    [self performSelector:@selector(remove) withObject:nil afterDelay:2];
}

- (void)remove
{
    [self removeFromParentAndCleanup:YES];
}

@end
