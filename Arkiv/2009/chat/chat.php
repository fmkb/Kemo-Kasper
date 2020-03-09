<?php

   /****************************************************/
   /* CoffeeCup Software Flash Chat                    */
   /* (C) 2005 CoffeeCup Software                      */
   /****************************************************/
   /* Companion Program For CoffeeCup Flash Chat       */
   /* Visit - http://www.coffeecup.com                 */
   /****************************************************/
   /* Constants   */
   /* Version     */ $version = '2.0';
   /* Date        */ $date = '04/24/06';
   /* Error Level */ error_reporting(E_ALL & ~E_NOTICE);

   $THE_ADMIN_USER = 'Administrator';
   $THE_ADMIN_PASS = '5014';
   $THE_WELCOME_MESSAGE = 'Welcome to Live Chat!';
   $AUTO_LOGOUT = 10 * 60;
   $PUBLIC_CHAT_ROOM_NAME='publicchat.txt';
   
   // Some general debugging options
   $debug = (isset($_REQUEST['debug'])) ? $_REQUEST['debug'] : $debug;
   if ($debug) error_reporting(E_ALL);
   // If debugging is enabled, print out the debugging info
   if ($debug)
   {
      switch($debug)
      {
         case 'info':
            exit(phpinfo());
         case 'pass':
            exit($THE_ADMIN_PASS);
      }
   }

   // Fixes SCRIPT_FILENAME for cgi folks
   if($_SERVER['PATH_TRANSLATED'])
   {
      $_SERVER['SCRIPT_FILENAME'] = $_SERVER['PATH_TRANSLATED'];
   }

   // The location of the generated preferences file
   $pref_file = substr_replace($_SERVER['SCRIPT_FILENAME'], 'xml', -3);

   // If the user makes a read request
   if($_GET['read'])
   {
   	  $ispublicroom=$_GET['ispublicroom'];
     
     	//IF ITS A PUBLIC ROOM 
     	if($ispublicroom=='true')
     	{
     		$file=$PUBLIC_CHAT_ROOM_NAME;
     	}
      else
      {
      	// The file the user is reading from
      	$file = $_GET['f'] . '_CC.txt';
			}
			
      // Check to see if the chat has ended
      if(!file_exists($file))
      {
         die('z=chatended');
      }

      // The last time the user recieved an update
      $last_checked = $_GET['t'];

      // Get the last time that the readfile was modified,
      // if we encounter an error, exit with an error message
      if(($last_updated = @filemtime($file)) === false)
      {
         die('z=couldntretrievefiletime');
      }

      // If the last update happened after the last time
      // we checked,proceed
      if($last_updated > $last_checked)
      {
         // Attempt to retrieve the contents of the file,
         // if we encounter an error, exit with an error message
         if(($contents = file_get_contents($file)) === false)
         {
           die('z=couldntgetfilecontents');
         }
         // If we succeeded in retrieving the contents, proceed
         else
         {
           // Split the chat up by line
           $chat_array = explode("\n", $contents);
           // Get rid of the garbage
           array_pop($chat_array);

           // For variable names if we have more than
           // one message
           $i = 1;

           // Itterate through the lines of the chat
           foreach($chat_array as $chat_line)
           {
             // Seperate the usernames and chat text
             $chat_line_array = explode('>', $chat_line);

             // more garbage
             array_pop($chat_line_array);
             array_shift($chat_line_array);


             // Extract all of the messages that took place
             // after the last time that messages were retrieved
             // from the file and format them
             if($chat_line_array[1] > $last_checked)
             {
                echo "mu$i=" . urlencode($chat_line_array[0]) . "&mm" .
                     $i++ . "=" . urlencode($chat_line_array[2]) . "&";
             }
           }

           // Let flash know the time
           echo "t=" . time();
        }
      }

      // Otherwise, there were no new messages
      else
      {
        die('z=nonewmessage');
      }
   }

   // If the user wants to write to a file
   else if($_GET['write'])
   {
   		$ispublicroom=$_GET['ispublicroom'];
     
     	//IF ITS A PUBLIC ROOM 
     	if($ispublicroom=='true')
     	{
     		$filename=$PUBLIC_CHAT_ROOM_NAME;
     	}
      else
      {
      	// The path to the log file
      	$filename = $_GET['f'] . '_CC.txt';
			}
      
      // Check to see if chat has ended
      if(!file_exists($filename))
      {
         die('z=chatended');
      }

      // The user sending the current message
      $username = $_GET['u'];
      // The current message
      $message = trim($_GET['m']);
      // The current time
      $timestamp = time();

      if($ispublicroom=='true')
     	{
	      // Grab the contents of the chat file
	      if(($contents = file_get_contents($filename)) === false)
	      {
	         die('z=couldntgetfilecontents');
	      }
	      
	      // Split the chat up by line
	      $chat_text = explode("\n", $contents);
	      // Get rid of the garbage
	      array_pop($chat_text);
	      
	      // count the number of lines
	      $count = count($chat_text);
	      
	      // if the number of lines is greater than fifty
	      // cut it back down to thirty
	      if($count > 60)
	      {
	         $chat_text = array_slice($chat_text, $count - 30); 
	      }
	
	      // convert chat text back to a string
	      $chat_text = implode("\n", $chat_text);
	      
	      $fp = fopen($filename, 'wb');
	      
	      if (!$fp)
	      {
	        die ('z=couldntopenfile');
	      }
	      
	      // Write out the message
	      $output = $chat_text . "\n>$username>$timestamp>$message>\n";
	      fwrite($fp, $output);
	      fclose($fp);
			}
			else
			{
      	// Attempt to open the log file
      	$fp = fopen($filename, 'wb');
      	if (!$fp)
	      {
	        die ('z=couldntopenfile');
	      }
	      
	      $output = ">$username>$timestamp>$message>\n";
	      fwrite($fp, $output);
	      fclose($fp);
    	}
    
      echo 'success';
   }

   // If a new user is logging in
   else if($_GET['userlogin'])
   {
   	 $ispublicroom=$_GET['ispublicroom'];
     
     //IF ITS A PUBLIC ROOM DONT CHECK TO SEE IF ADMIN IS LOGGED IN
     if($ispublicroom=='false')
     {
	     // If the admin isn't logged in, die with an error
	     if(!file_exists('loggedin_CC.txt'))
	     {
	        die('z=adminunavailable');
	     }
		 }
		 
     // Create a buffer for the username
     $username = $_GET['username'];

     // Initialize a counter
     $i = 0;

     // If someone with the same name is already logged ing,
     // append a number to the end of the user name
     while(file_exists($username . '_CC.txt'))
     {
       $username = $_GET['username'] . ++$i;
     }

     // Parse the preferences file
     if(($prefs = parse_xml($pref_file)) === false)
     {
        die('z=unabletoparsexml');
     }

     // Get the welcome message
     $THE_WELCOME_MESSAGE = $prefs->welcomemsg;

		 if($ispublicroom=='true')
		 {
		 	  // Attempt to open the log file
       $fp = fopen($PUBLIC_CHAT_ROOM_NAME , 'ab'); 
       fclose($fp);   
  	 }
		 else
		 {
	     // Attempt to open the log file
	     $fp = fopen($username . '_CC.txt', 'ab');
	      
	     // If we cannot open the output file
	     // exit with an error message
	     if (!$fp)
	     {
	        die ('z=couldntopenfile');
	     }
	     
	     // Write out the welcome message
	     fwrite($fp, ">>>>>$_GET[e]>>>>>\n>  >" . time() . ">$THE_WELCOME_MESSAGE>\n");
	     // Close the log file
	     fclose($fp);
		 }
     
     // Let flash know the username
     die('username=' .  $username .  '&z=adminavailable');
   }

   // If the client needs to know if the admin is available
   else if($_GET['adminavailable'])
   {
     // If the admin isn't logged in, die with an error
     if(!file_exists('loggedin_CC.txt'))
     {
        die('z=adminunavailable');
     }
     // Otherwise, die with a success message
     else
     {
        die('z=adminavailable');
     }
   }

   // If the admin script wants to know who is logged in
   else if($_GET['checkForUsers'])
   {
      // validate their username and password
      if($_GET['u'] == $THE_ADMIN_USER && $_GET['p'] == $THE_ADMIN_PASS)
      {
          // Open the directory
          if ($dh = opendir('./'))
          {
             // look for files
             while (($file = readdir($dh)) !== false)
             {
                // that end in ccc
                if(substr($file, -7) == '_CC.txt' && $file != 'loggedin_CC.txt')
                {
                   // Check to see if the users session has timed out
                   if(@filemtime($file) <= (time() - $AUTO_LOGOUT))
                   {
                      $f = "This chat sessions was terminated due to inactivity at " .
                           date("g:ia") . ".\n" .file_get_contents($file);
                      preg_match('/>>>>>(.*)>>>>>/', $f, $matches);
                      @mail($matches[1], 'Chat Log', format_email($f),
                            "From: noreply@{$_SERVER[SERVER_NAME]}");

                      // delete the users file
                      @unlink($file);
                   }
                   // and add those files to the users list
                   else
                   {
                      $users[] = substr_replace($file, '', -7);
                   }
                }
             }
             // clean up
             closedir($dh);
          }


          // If no users are logged in, display the nousers message
          if(count($users) == 0)
          {
             die('z=nousers');
          }
          // Otherwise, print out all the usernames
          else
          {
             $i = 0;
             foreach($users as $user)
             {
                echo 'u' . $i++ . "=$user&";
             }
             die();
          }
      }
      // otherwise, access is denied
      else
      {
         die('z=accessdenied');
      }
   }

   // If the administrator is trying to log in through via the web
   else if($_GET['login'])
   {
      // validate their username and password
      if($_GET['u'] == $THE_ADMIN_USER && $_GET['p'] == $THE_ADMIN_PASS)
      {
         // Attempt to open the log file
         $fp = fopen('loggedin_CC.txt', 'ab');
         // If we cannot open the output file
         // exit with an error message
         if (!$fp)
         {
            die ('z=couldntopenfile');
         }
         // Write out a message
         fwrite($fp, "loggedin");
         // Close the log file
         fclose($fp);

         die('z=accessgranted');
      }
      // Or exit, accessdenied
      else
      {
         die('z=accessdenied');
      }
   }

   else if($_GET['logout'])
   {
      // validate their username and password
      if($_GET['u'] == $THE_ADMIN_USER && $_GET['p'] == $THE_ADMIN_PASS)
      {
          // Open the directory
          if ($dh = opendir('./'))
          {
             // look for files
             while (($file = readdir($dh)) !== false)
             {
                // that end in ccc
                if(substr($file, -7) == '_CC.txt')
                {
                  $f = "This chat sessions was terminated by the administrator at " .
                        date("g:ia") . ".\n" .file_get_contents($file);
                  preg_match('/>>>>>(.*)>>>>>/', $f, $matches);
                  @mail($matches[1], 'Chat Log', format_email($f),
                        "From: noreply@{$_SERVER[SERVER_NAME]}");

                   // delete the users file
                   @unlink($file);
                }
             }
          }
          closedir($dh);

          // delete the file
          if(@unlink('loggedin_CC.txt') !== false)
          {
              die('z=removed');
          }
          // or display an error message
          else
          {
              die('z=unabletoremove');
          }
      }
      // otherwise, exit with an error message
      else
      {
         die('z=accessdenied');
      }
   }

   // If the administrator wants to end a chat session
   else if($_GET['endSession'])
   {
      // validate their username and password
      if($_GET['u'] == $THE_ADMIN_USER && $_GET['p'] == $THE_ADMIN_PASS)
      {
          $file = "This chat sessions was terminated by the administrator at " .
                  date("g:ia") . ".\n" . file_get_contents($_GET['f'] . '_CC.txt');

          preg_match('/>>>>>(.*)>>>>>/', $file, $matches);

          @mail($matches[1], 'Chat Log', format_email($file),
                "From: noreply@{$_SERVER[SERVER_NAME]}");

          // delete the file
          if(@unlink($_GET['f'] . '_CC.txt') !== false)
          {
              die('z=removed');
          }
          // or display an error message
          else
          {
              die('z=unabletoremove');
          }
      }
      // otherwise, exit with an error message
      else
      {
         die('z=accessdenied');
      }

   }

   // This class holds some general user inputted preferences for
   // the look of the chat page
   class Pref
   {
      // The html to embed the swf
      var $htmlswf;

      // This function builds the chat object given an array of
      // attributes
      function Pref ($attr)
      {
              // Itterates through all the given attributes
         foreach ($attr as $name => $value)
         {
              $this->$name = $value;
         }
      }
   }

   // Parses an xml file and builds an array of Page Object out of it.
   // Return the array of page objects or false if an error occurs.
   function parse_xml ($xml_file)
   {
      // Reads the xml file into a string
      $xml = file_get_contents($xml_file);

      $parser = xml_parser_create();
      // Disables casefolding for semantical purposes
      xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
      // Skips values that consist of only white space
      xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
      // If the xml fails to parse into a struct, we return false
      if(xml_parse_into_struct($parser, $xml, $values, $tags) === 0)
      {
         return false;
      }
      xml_parser_free($parser);

      foreach ($tags as $name => $value)
      {
         // If we're looking at the prefs file, load the prefs object
         if ($name == 'coffeecupchat')
         {
            return new Pref($values[0]['attributes']);
         }
      }
      return false;
   }

   function format_email($email)
   {
      return preg_replace(array('/>>>>>[^>]*>>>>>/',
                                '/(\W+)>([^>]+)>[^>]+>([^>]*)>/'),
                          array('',
                                '\1[\2] \3'),
                           $email);
   }

?>