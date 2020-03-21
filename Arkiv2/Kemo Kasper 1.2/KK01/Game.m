//
//  Game.m
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 6/19/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import "Game.h"

@implementation Game

CWL_SYNTHESIZE_SINGLETON_FOR_CLASS(Game);

- (void)initialize
{
    [self loadProperties];
    _currentRound = 0; // 0 is correct (can be set to something else for testing other rounds)
    _currentPatient = 0;
    _currentPatientFrame = 0;
    _score = 0;
    _finished = NO;
    //Cancer cells stats
    _cancerCellsSpawned = 0;
    _cancerCellsKilledByYou = 0;
    _cancerCellsKilledByKemoKasper = 0;
    _cancerCellsKilledByYouPreviousRound = 0;
    _cancerCellsKilledByKemoKasperPreviousRound = 0;
    
    //Points
    _pointsPerKillByYou = 15;
    _pointsPerKillByKemoKasper = 10;
    _bonusKillPoints = 75;
    _bonusSavePoints = 50;
    _bonusSavePointsKK = 100;
    
    [self initializeNextRound];
}

- (void)initializeNextRound
{
    _usedKemoKasper = NO;
    _kemoKasperLastInnocentKillCounter = 0;
    //Cancer cells stats
    _cancerCellsSpawned = 0;
    _cancerCellsOnScreen = 0;
    _cancerCellsKilledByYouThisRound = 0;
    _cancerCellsKilledByKemoKasperThisRound = 0;
    _bonusKills = 0;
    _bonusSaves = 0;
    //User performance
    _numberOfKills = 0;
    _averageKillToKillTime = 0;
    //Arrays
    [Game sharedGame].cells = [CCArray array];
    [Game sharedGame].cancerCells = [CCArray array];
    [Game sharedGame].innocentTimeCells = [CCArray array];
    [Game sharedGame].innocentBonusCells = [CCArray array];
}

- (void)updateScore:(int)score
{
    _score = score;
    if(score > _theHighScore) {
        [self newHighScore:score];
    }
}

- (void)newHighScore:(int)highScore
{
    _theHighScore = highScore;
    
    NSUserDefaults *properties = [NSUserDefaults standardUserDefaults];
    [properties setInteger:_theHighScore forKey:@"TheHighScore"];
    [properties synchronize];
}

- (void)loadProperties
{
    NSUserDefaults *properties = [NSUserDefaults standardUserDefaults];
    NSString *propertyName = @"TheHighScore";
    if([properties objectForKey:propertyName]) {
        _theHighScore = [properties integerForKey:propertyName];
    } else {
        _theHighScore = 0;
        [properties setInteger:_theHighScore forKey:propertyName];
    }
    [properties synchronize];
    
    _roundDuration = 30;
    _bonusTime = 1;
    _cancerCellsSpeed = 17;
    _cancerCellsInitialNumber = 8;
    _cancerCellsMinNumber = 7;
    _cancerCellsMaxNumber = 18;
    _cancerCellsNumber = 500; // 500 is correct
    _cancerCellsMultiplyingRate = 3;
    _cancerCellsInterval = 1;
    _kemoKasperShowTime = 2;
    _innocentTimeCellsMaxNumber = 2;
    _innocentTimeCellsInterval = 2;
    _innocentBonusCellsMaxNumber = 8;
    _innocentBonusCellsInterval = 2;
}

- (int)cancerCellsKilled
{
    return _cancerCellsKilledByYou + _cancerCellsKilledByKemoKasper;
}

- (int)cancerCellsKilledPreviousRound
{
    return _cancerCellsKilledByYouPreviousRound + _cancerCellsKilledByKemoKasperPreviousRound;
}

- (int)cancerCellsLeft
{
    return _cancerCellsNumber - [self cancerCellsKilled];
}

- (BOOL)cancerDefeated
{
    return [self cancerCellsKilled] >= _cancerCellsNumber;
}

- (CGFloat)kill
{
    _numberOfKills++;
    NSDate *now = [NSDate date];
    if(_lastKillTime) {
        NSTimeInterval seconds = [now timeIntervalSinceDate:_lastKillTime];
        _averageKillToKillTime = (_averageKillToKillTime + seconds) / 2;
    }
    _lastKillTime = now;
    return _averageKillToKillTime;
}

@end
