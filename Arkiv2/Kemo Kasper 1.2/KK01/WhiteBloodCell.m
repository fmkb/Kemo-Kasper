//
//  WhiteBloodCell.m
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 6/17/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import "WhiteBloodCell.h"

@implementation WhiteBloodCell

- (id)initAtPosition:(CGPoint)initialPosition
{
    self = [super initWithSpriteFrameName:@"white_blood_cell/0000"
                               atPosition:initialPosition];
    if(self) {
        self.tag = WHITE_BLOOD_CELL;
    }
    return self;
}

#pragma mark Remove

- (void)remove
{
    [super remove];
}

@end
