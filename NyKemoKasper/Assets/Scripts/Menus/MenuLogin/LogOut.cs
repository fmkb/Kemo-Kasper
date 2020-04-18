using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;

public class LogOut : MonoBehaviour
{
    public Button logOut;
    
    private string email;
    
    private string password;

    void Start()
    {
        logOut.onClick.AddListener(LogMeOut);
    }

    void LogMeOut()
    {
        PlayerPrefs.SetString("Email", "");
        PlayerPrefs.SetString("Password", "");

        SceneManager.LoadScene("LoginMenu");
    }
}
