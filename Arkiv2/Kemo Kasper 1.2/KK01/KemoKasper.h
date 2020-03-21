//
//  KemoKasper.h
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 6/12/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import "GameActor.h"

#import "CancerCell.h"

@interface KemoKasper : GameActor {
    
    int speed;
    CCArray *points;
    
}

@property (nonatomic, assign) BOOL injected;

- (id)initAtPosition:(CGPoint)initialPosition;

- (void)inject;
- (void)stompAtPoint;
- (void)stomp;
- (void)moveOut;

@end
