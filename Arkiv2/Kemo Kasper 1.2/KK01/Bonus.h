//
//  Bonus.h
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 8/22/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import "CCLayer.h"

@interface Bonus : CCLayer {
    
    CCLabelTTF *label;
    CCSprite *icon;
    
}

- (id)initWithPoints:(int)points
          atPosition:(CGPoint)position
           withColor:(ccColor3B)color;

@end
