using System.Collections;
using UnityEngine;
using UnityEngine.Networking;
using UnityEngine.UI;

[System.Serializable]
public class LoginUser : MonoBehaviour
{
    public InputField emailInput;
    public InputField passwordInput;

    public Button loginButton;
    public Button submitButton;

    private LoginCredentials loginCredentials = new LoginCredentials();
    
    private string email;
    
    private string password;

    public bool isLoggedIn;

    public GameObject warning;

    public string message;

    public GameObject textIfUnlogged;
    public GameObject textIfLogged;

    void Start()
    {
        submitButton.onClick.AddListener(CreateToken);
        RemoveWarning();
        message = ""; 
        GetToken();
        textIfLogged.SetActive(false);
        textIfUnlogged.SetActive(true);

        if (isLoggedIn)
        {
            DisplayButtonName(email);
        }

        // getting a request from the website
        StartCoroutine(GetRequest("https://api.kemo-kasper.dk/hello.json"));
    }

    public void RemoveWarning()
    {
        // disabling the warning
        warning.gameObject.SetActive(false);
    }

    public void EmptyFields()
    {
        // emptying the input fields
        passwordInput.text = "";
        emailInput.text = "";
    }

    public void CreateToken()
    {
        if (emailInput.gameObject.activeInHierarchy)
        {
            // if the email input is not empty and the password is at lesat 6 characters
            if (emailInput.text != "" && passwordInput.text.Length > 6)
            {
                // checking for existing account here

                // creating a login token, so the user is logged in automatically the next time
                string name = "";
                int game1HighestLVL = 1;
                int game2HighestLVL = 1;

                PlayerPrefs.SetString("Name", name);
                PlayerPrefs.SetString("Email", emailInput.text);
                PlayerPrefs.SetString("Password", passwordInput.text);
                PlayerPrefs.SetInt("Game1HighestLVLLogin", game1HighestLVL);
                PlayerPrefs.SetInt("Game2HighestLVLLogin", game2HighestLVL);

                isLoggedIn = true;
                GameObject.Find("LoginMenuScripts").GetComponent<LoginMenuNavigation>().OpenMainMenuLogin();
                RemoveWarning();
            }
            else
            {
                // if email field is empty or password is too short or a user couldn't be found in the database, display warning
                passwordInput.text = "";
                warning.gameObject.SetActive(true);
            }
        }
    }

    public void GetToken()
    {
        // getting the login token from playerprefs
        loginCredentials.Email = PlayerPrefs.GetString("Email");
        loginCredentials.Password = PlayerPrefs.GetString("Password");

        // if either email or password is empty, do not log in
        if (loginCredentials.Email == "" || loginCredentials.Password == "")
        {
            isLoggedIn = false;
        }
        else
        {
            // if was logged in before and the token exists, automatically log in
            email = loginCredentials.Email;
            password = loginCredentials.Password;
            isLoggedIn = true;
        }
    }

    public void DisplayButtonName(string name)
    {
        textIfLogged.SetActive(true);
        textIfUnlogged.SetActive(false);

        if (PlayerPrefs.HasKey("Name"))
        {
            textIfLogged.transform.GetChild(0).GetComponent<Text>().text += PlayerPrefs.GetString("Name");
            textIfLogged.transform.GetChild(1).GetComponent<Text>().text += PlayerPrefs.GetString("Name");
            textIfLogged.transform.GetChild(2).GetComponent<Text>().text += PlayerPrefs.GetString("Name");
        }
        else
        {
            textIfLogged.transform.GetChild(0).GetComponent<Text>().text += PlayerPrefs.GetString("Email");
            textIfLogged.transform.GetChild(1).GetComponent<Text>().text += PlayerPrefs.GetString("Email");
            textIfLogged.transform.GetChild(2).GetComponent<Text>().text += PlayerPrefs.GetString("Email");
        }
    }

    IEnumerator GetRequest(string uri)
    {
        using (UnityWebRequest webRequest = UnityWebRequest.Get(uri))
        {
            yield return webRequest.SendWebRequest();

            string[] pages = uri.Split('/');
            int page = pages.Length - 1;

            if (webRequest.isNetworkError)
            {
                Debug.Log(pages[page] + ": Error: " + webRequest.error);
            }
            else
            {
                Debug.Log(pages[page] + ":\nReceived: " + webRequest.downloadHandler.text);

            }
        }
    }
}
