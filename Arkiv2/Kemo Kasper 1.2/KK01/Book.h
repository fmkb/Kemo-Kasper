//
//  Book.h
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 8/27/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "CWLSynthesizeSingleton.h"

@interface Book : NSObject

CWL_DECLARE_SINGLETON_FOR_CLASS(Book);

@property (nonatomic, assign) int timeToSwipeInstruction;

- (void)initialize;
- (void)loadProperties;
- (void)saveProperties;

@end
