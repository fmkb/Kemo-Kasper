using System.Collections;
using System.Collections.Generic;
using System.IO;
using UnityEngine;
using UnityEngine.UI;

[System.Serializable]
public class LoginUser : MonoBehaviour
{
    public InputField emailInput;
    public InputField passwordInput;

    public Button loginButton;
    public Button submitButton;

    private LoginCredentials loginCredentials = new LoginCredentials();

    [SerializeField]
    private string email;

    [SerializeField]
    private string password;

    public bool isLoggedIn;

    public GameObject warning;

    void Start()
    {
        submitButton.onClick.AddListener(CreateToken);
        RemoveWarning();

        GetToken();

        if(isLoggedIn)
        {
            DisplayButtonName(email);
        }
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

                loginCredentials.Email = emailInput.text;
                loginCredentials.Password = passwordInput.text;

                string path = Application.dataPath + "/Resources/Login/LoginCredentials.json";
                string prefs = JsonUtility.ToJson(loginCredentials, true);

                File.WriteAllText(path, prefs);

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
        string json = File.ReadAllText(Application.dataPath + "/Resources/Login/LoginCredentials.json");
        loginCredentials = JsonUtility.FromJson<LoginCredentials>(json);
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
}
