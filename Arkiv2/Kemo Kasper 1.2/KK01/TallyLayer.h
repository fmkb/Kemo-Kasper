//
//  TallyLayer.h
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 8/20/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import "CCBReader.h"
#import "CCControlButton.h"

#import "GameLayer.h"
#import "PointsBonus.h"
#import "RoundBonus.h"

@interface TallyLayer : CCLayer {
    
    CCLayer *remainingCancer;
    CCLabelTTF *killedByYou;
    CCLabelTTF *killedByKemoKasper;
    CCLabelTTF *score;
    CCLabelTTF *highscore;
    
    CCControlButton *button;
    
    int bonusPoints;
    int bonusPointsCounter;
    int bonusPointsCounterDown;
    int roundBonus;
    int roundBonusCounter;
    int roundBonusCounterDown;
}

@end
