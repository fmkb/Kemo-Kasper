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

    void Start()
    {
        submitButton.onClick.AddListener(CreateToken);
        RemoveWarning();
        message = ""; 
        GetToken();

        if(isLoggedIn)
        {
            DisplayButtonName(email);
        }

        StartCoroutine(GetRequest("http://api.kemo-kasper.dk/hello.json"));
    }

    public void RemoveWarning()
    {
        warning.gameObject.SetActive(false);
    }

    public void EmptyFields()
    {
        passwordInput.text = "";
        emailInput.text = "";
    }

    public void CreateToken()
    {
        if (emailInput.gameObject.activeInHierarchy)
        {
            if (emailInput.text != "" && passwordInput.text != "")
            {
                PlayerPrefs.SetString("Email", emailInput.text);
                PlayerPrefs.SetString("Password", passwordInput.text);

                isLoggedIn = true;
                GameObject.Find("LoginMenuScripts").GetComponent<LoginMenuNavigation>().OpenMainMenuLogin();
                RemoveWarning();
            }
            else
            {
                passwordInput.text = "";
                warning.gameObject.SetActive(true);
            }
        }
    }

    public void GetToken()
    {
        loginCredentials.Email = PlayerPrefs.GetString("Email");
        loginCredentials.Password = PlayerPrefs.GetString("Password");

        if (loginCredentials.Email == "" || loginCredentials.Password == "")
        {
            isLoggedIn = false;
        }
        else
        {
            email = loginCredentials.Email;
            password = loginCredentials.Password;
            isLoggedIn = true;
        }
    }

    public void DisplayButtonName(string name)
    {
        loginButton.transform.GetChild(0).GetComponent<Text>().text = "Spil som " + email;
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
