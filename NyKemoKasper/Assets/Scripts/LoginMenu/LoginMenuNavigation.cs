using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;

public class LoginMenuNavigation : MonoBehaviour
{
    public Button loginButton;
    public Button noLoginButton;

    public Button loginTabButton;
    public Button registerTabButton;

    public Button goBackButton;
    public Button continueButton;
    public Button playNoLoginButton;

    public GameObject loginRegisterCanvas;
    public GameObject loginCanvas;
    public GameObject registerCanvas;
    public GameObject mainCanvas;
    
    private LoginUser loginScript;
    private bool isLoggedIn;

    void Start()
    {
        GoBackToMain();
        SwitchToLoginTab();
        mainCanvas.gameObject.SetActive(true);

        loginButton.onClick.AddListener(OpenLoginRegisterCanvas);
        loginTabButton.onClick.AddListener(SwitchToLoginTab);
        registerTabButton.onClick.AddListener(SwitchToRegisterTab);
        goBackButton.onClick.AddListener(GoBackToMain);
        playNoLoginButton.onClick.AddListener(OpenMainMenuNoLogin);

        loginScript = this.GetComponent<LoginUser>();
        isLoggedIn = loginScript.isLoggedIn;
    }
    
    public void OpenLoginRegisterCanvas()
    {
        isLoggedIn = loginScript.isLoggedIn;
        if (isLoggedIn)
        {
            GoBackToMain();
            mainCanvas.gameObject.SetActive(false);
            SceneManager.LoadScene("MainMenuLogin");
        }
        else
        {
            loginRegisterCanvas.gameObject.SetActive(true);
        }
    }

    public void SwitchToLoginTab()
    {
            registerCanvas.gameObject.SetActive(false);
            loginCanvas.gameObject.SetActive(true);
            loginTabButton.GetComponent<Image>().color = new Color32(0, 255, 25, 50);
            registerTabButton.GetComponent<Image>().color = new Color32(255, 25, 0, 50);
    }

    public void SwitchToRegisterTab()
    {
        registerCanvas.gameObject.SetActive(true);
        loginCanvas.gameObject.SetActive(false);
        registerTabButton.GetComponent<Image>().color = new Color32(0, 255, 25, 50);
        loginTabButton.GetComponent<Image>().color = new Color32(255, 25, 0, 50);
    }

    public void GoBackToMain()
    {
        loginRegisterCanvas.gameObject.SetActive(false);
    }

    public void OpenMainMenuLogin()
    {
            GoBackToMain();
            mainCanvas.gameObject.SetActive(false);
            SceneManager.LoadScene("MainMenuLogin");
    }

    public void OpenMainMenuNoLogin()
    {
            GoBackToMain();
            mainCanvas.gameObject.SetActive(false);
            SceneManager.LoadScene("MainMenuNoLogin");
    }
}
