//
//  CancerCell.m
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 6/12/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import "CancerCell.h"

@implementation CancerCell

- (id)initAtPosition:(CGPoint)initialPosition
          isDoubling:(BOOL)doubling
{
    return [self initAtPosition:initialPosition inMode:NO withFrame:nil withSpeed:30 isDoubling:doubling];
}

- (id)initAtPosition:(CGPoint)initialPosition
           withFrame:(NSString*)frameName
          isDoubling:(BOOL)doubling
{
    return [self initAtPosition:initialPosition inMode:YES withFrame:frameName withSpeed:0 isDoubling:doubling];
}

- (id)initAtPosition:(CGPoint)initialPosition
           withSpeed:(CGFloat)speed
          isDoubling:(BOOL)doubling
{
    return [self initAtPosition:initialPosition inMode:NO withFrame:nil withSpeed:speed isDoubling:doubling];
}

- (id)initAtPosition:(CGPoint)initialPosition
              inMode:(BOOL)immiediate
          isDoubling:(BOOL)doubling
{
    return [self initAtPosition:initialPosition inMode:immiediate withFrame:nil withSpeed:30 isDoubling:doubling];
}

- (id)initAtPosition:(CGPoint)initialPosition
           inMode:(BOOL)immiediate
        withFrame:(NSString*)frameName
           withSpeed:(CGFloat)speed
          isDoubling:(BOOL)doubling
{
    _doubling = doubling;
    NSString *frame;
    if(!immiediate) {
        frame = (frameName) ? frameName : @"cancer_appear/0000";
    } else {
        frame = (frameName) ? frameName : @"cancer_waddle/0000";
    }
    self = [super initWithSpriteFrameName:frame
                               atPosition:ccp(initialPosition.x, initialPosition.y)];
    if(self) {
        self.anchorPoint = ccp(0.5, 0);
        self.tag = CANCER_CELL;
        _speed = speed;
        self.animation = [CCAnimation animationWithSpriteFrames:[Animations sharedAnimations].cancerCellAnimationFrames delay:0.04f];
        if(!immiediate) {
            [self appear];
        } else {
            if(!frameName) {
                [self animate];
                [self move];
            }
        }
    }
    return self;
}

- (void)onEnter
{
    [super onEnter];
}

- (void)setDelegate:(id)delegate
{
    [super setDelegate:delegate];
    [_delegate addedCancerCell:_doubling];
}

#pragma mark Movement and Behaviour

- (void)appear
{
    CCAnimation *appearAnimation = [CCAnimation animationWithSpriteFrames:[Animations sharedAnimations].cancerCellAppearFrames delay:0.03f];
    CCSequence *sequence = [CCSequence actions:[CCAnimate actionWithAnimation:appearAnimation],
                            [CCCallBlock actionWithBlock:^{
        [self animate];
        [self move];
    }], nil];
    [self runAction:sequence];
}

- (void)move
{
    //Set destination
    float x = ((arc4random() % (int)self.position.y) - self.position.y/2) + self.position.x;
    float y = 0;
    if(_doubling) {
        if(UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPhone)
            y = arc4random() % 100 + 50;
        else
            y = arc4random() % 200 + 100;
    } else {
        y = -self.contentSize.height;
    }
    
    //Set speed
    CGFloat distance = [[KKUtils sharedKKUtils] distanceFrom:self.position to:ccp(x,y)];
    _speed = [self getAdjustedSpeed:distance];
    
    //MOVE
    id moveAction = [CCMoveTo actionWithDuration:_speed position:ccp(x, y)];
    CCFiniteTimeAction *easedAction = [CCEaseOut actionWithAction:moveAction rate:1];
    CCSequence *sequence;
    if(_doubling) {
        sequence = [CCSequence actions:easedAction,
                    [CCCallFunc actionWithTarget:self selector:@selector(split)], nil];
    } else {
        sequence = [CCSequence actions:easedAction,
                    [CCCallFunc actionWithTarget:self selector:@selector(removeOnly)], nil];
    }
    sequence.tag = MOVE_ACTION;
    [self runAction:sequence];
}

- (void)split
{
    //Split cancer cell
    CancerCell *splitCell = [[CancerCell alloc] initAtPosition:ccp(self.position.x, self.position.y)
                                                  withFrame:@"cancer_split/0000" isDoubling:NO];
    splitCell.delegate = self.delegate;
    splitCell.zOrder = self.zOrder;
    CCAnimation *splitAnimation = [CCAnimation animationWithSpriteFrames:[Animations sharedAnimations].cancerCellFullSplitAnimationFrames delay:0.05];
    
    //Remove walking cancer cell
    [self removeOnly];
    [[Animations sharedAnimations].cellsBatch addChild:splitCell];
    [[Game sharedGame].cancerCells addObject:splitCell];
    [[Game sharedGame].cells addObject:splitCell];
    
    //Run explosion animation
    CCSequence *sequence = [CCSequence actions:[CCAnimate actionWithAnimation:splitAnimation],
                            [CCCallBlock actionWithBlock:^{
        [_delegate addCancerCellAtPoint:ccp(splitCell.position.x-30*self.scale, splitCell.position.y) isDoubling:NO inMode:YES];
        [_delegate addCancerCellAtPoint:ccp(splitCell.position.x+30*self.scale, splitCell.position.y) isDoubling:NO inMode:YES];
        [splitCell removeOnly];
        
    }], nil];
    sequence.tag = MOVE_SPLIT;
    [splitCell runAction:sequence];
}

- (BOOL)splitting
{
    return ([originalFrame isEqualToString:@"cancer_split/0000"]);
}

- (void)moveDown
{
    [self stopActionByTag:MOVE_ACTION];
    int randomX =  arc4random() % 200 + self.position.x;
    int randomTime = arc4random() % 5 + _speed*(self.position.y/(winSize.height*0.7));
    id moveAction = [CCMoveTo actionWithDuration:randomTime position:ccp(randomX, -(self.contentSize.height*self.scale)/2)];
    CCSequence *sequence = [CCSequence actions:moveAction,
                            [CCCallFunc actionWithTarget:self selector:@selector(removeOnly)], nil];
    sequence.tag = MOVE_DOWN_ACTION;
    [self runAction:sequence];
}

- (CGFloat)getAdjustedSpeed:(CGFloat)distance
{
    CCArray *cancerCells = [Game sharedGame].cancerCells;
    
    //Adjust by current amount and positions of cancer cells
    int tier1 = 0, tier2 = 0, tier3 = 0, tier4 = 0;
    for(CancerCell *cell in cancerCells) {
        if(cell.position.y < winSize.height*0.25) tier1++;
        else if(cell.position.y < winSize.height*0.5) tier2++;
        else if(cell.position.y < winSize.height*0.75) tier3++;
        else tier4++;
    }
    CGFloat speed = 30;
    if(tier1==0 && tier2==0 && tier3==0) speed = 3;
    else if(tier1 == 0 && tier2 == 0) speed = 6;
    else if(tier1 == 0 && tier2 < tier3) speed = 9;
    else if(tier1 == 0 && tier2 > tier3) speed = 12;
    else if(tier1 > tier2 && tier1 < tier3) speed = 20;
    else if(tier1 > tier2 && tier1 > tier3) speed = 30;
    
    //Adjust by current Y coordinate
    speed /= winSize.height/distance;
    
    return speed/([Game sharedGame].cancerCellsSpeed/10);
}

#pragma mark Touches

- (void)touchAction
{
    [super touchAction];
    [self stop];
    if(self.active) {
        self.active = NO;

        //White blood cell
        WhiteBloodCell *wbc = [[WhiteBloodCell alloc] initAtPosition:ccp(self.position.x+10*self.scale, self.position.y+54*self.scale)];
        [[Animations sharedAnimations].cellsBatch addChild:wbc z:winSize.height*100*self.scale-1];
        [wbc removeAnimated];
        [[SoundEffects sharedSoundEffects] performSelector:@selector(playerKillCancer) withObject:nil afterDelay:0];
        
        //Exploding cancer cell
        CancerCell *explodingCell = [[CancerCell alloc] initAtPosition:ccp(self.position.x, self.position.y-44*self.scale)
                                                          withFrame:@"cancer_waddle_explode/0000" isDoubling:NO];
        explodingCell.zOrder = self.zOrder;
        explodingCell.touchable = NO;
        CCAnimation *explodeAnimation = [CCAnimation animationWithSpriteFrames:[Animations sharedAnimations].cancerCellExplodeAnimationFrames delay:0.01f];
        
        //Remove walking cancer cell
        float delay = 0.2;
        [self scheduleOnce:@selector(remove) delay:delay];
        [[Animations sharedAnimations].cellsBatch performSelector:@selector(addChild:) withObject:explodingCell afterDelay:delay];
        
        //Run explosion animation
        CCSequence *sequence = [CCSequence actions:[CCDelayTime actionWithDuration:delay],
                                [CCAnimate actionWithAnimation:explodeAnimation],
                                [CCCallFunc actionWithTarget:explodingCell selector:@selector(removeOnly)],
                                [CCCallBlock actionWithBlock:^{
            if(self.splitting) {
                [[SoundEffects sharedSoundEffects] playerSavedInnocentSound];
            } else {
                [[SoundEffects sharedSoundEffects] playerKillCancerPop];
            }
        }], nil];
        [explodingCell runAction:sequence];
        
        //Add new cancer cells if necessary
        float timeToKill = [[Game sharedGame] kill];
        float timeAdjustment = (float)[Game sharedGame].roundDuration/(float)[_delegate getTime];
        if(timeToKill > 0 && timeAdjustment > 0 && timeAdjustment < 10) {
            float rate = [Game sharedGame].cancerCellsMultiplyingRate;
            float newCells = ((1/(timeToKill*10)) * rate);
            int power = ([Game sharedGame].currentRound > 0) ? 2 : 1;
            newCells = newCells * powf(timeAdjustment,power);
            [_delegate addCancerCells:(int)newCells];
        }
    }
}

@end
