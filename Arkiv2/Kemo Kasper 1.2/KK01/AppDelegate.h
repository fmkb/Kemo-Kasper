//
//  AppDelegate.h
//  Kemo Kasper
//
//  Created by Konrad Bajtyngier on 6/12/13.
//  Copyright (c) 2013 UOVO. All rights reserved.
//

#import <UIKit/UIKit.h>

#import "IntroLayer.h"

@interface AppDelegate : UIResponder <UIApplicationDelegate, CCDirectorDelegate>
{
    
	UIWindow *window_;
	UINavigationController *navController_;
    
    CCDirectorIOS *director_;
}

@property (nonatomic, retain) UIWindow *window;
@property (readonly) UINavigationController *navController;
@property (readonly) CCDirectorIOS *director;

@property (readonly, strong, nonatomic) NSManagedObjectContext *managedObjectContext;
@property (readonly, strong, nonatomic) NSManagedObjectModel *managedObjectModel;
@property (readonly, strong, nonatomic) NSPersistentStoreCoordinator *persistentStoreCoordinator;

- (void)saveContext;
- (NSURL *)applicationDocumentsDirectory;

@end
