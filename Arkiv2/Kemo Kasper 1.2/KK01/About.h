//
//  About.h
//  Kemo-Kasper og hans jagt på de sure kræftceller
//
//  Created by Konrad Bajtyngier on 03/03/14.
//  Copyright (c) 2014 UOVO. All rights reserved.
//

#import "CCLayer.h"

@interface About : CCLayer <UIAlertViewDelegate> {
    
    CCLabelTTF *text;
    CCLabelTTF *madeBy;
    
    int correctAnswer;
    
}

@end
