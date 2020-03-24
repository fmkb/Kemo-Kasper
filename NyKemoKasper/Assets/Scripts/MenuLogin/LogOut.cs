using System.Collections;
using System.Collections.Generic;
using System.IO;
using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;

public class LogOut : MonoBehaviour
{
    public Button logOut;

    private LoginCredentials loginCredentials = new LoginCredentials();

    [SerializeField]
    private string email;

    [SerializeField]
    private string password;

    void Start()
    {
        logOut.onClick.AddListener(LogMeOut);
    }

    void LogMeOut()
    {
        loginCredentials.Email = null;
        loginCredentials.Password = null;

        string path = Application.dataPath + "/Resources/Login/LoginCredentials.json";
        string prefs = JsonUtility.ToJson(loginCredentials, true);

        File.WriteAllText(path, prefs);

        SceneManager.LoadScene("LoginMenu");
    }
}
