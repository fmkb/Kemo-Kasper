//
//  BonusCancer.h
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 8/24/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import "CCNode.h"
#import "CCLayer+Opaque.h"

@interface BonusCancer : CCNode {
    
    CCLabelTTF *label;
    
}

- (void)setPoints:(int)points;

@end
