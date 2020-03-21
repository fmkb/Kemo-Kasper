//
//  CCMenuItemImage+Scalable.m
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 8/26/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import "CCMenuItemImage+Scalable.h"

@implementation CCMenuItemImage (Scalable)

- (void)selected {
    [super selected];
    [self runAction:[CCScaleTo actionWithDuration:0.05 scale:self.scale*1.1f]];
}

- (void)unselected {
    [super unselected];
    [self runAction:[CCScaleTo actionWithDuration:0.05 scale:self.scale/1.1f]];
}

- (void)activate {
    [self runAction:[CCSequence actions:
                     [CCScaleTo actionWithDuration:0.15 scale:self.scale/1.1f],
                     [CCDelayTime actionWithDuration:0.02],
                     [CCCallBlock actionWithBlock:^(void) { [super activate]; }],
                     nil
                     ]];
}

@end
