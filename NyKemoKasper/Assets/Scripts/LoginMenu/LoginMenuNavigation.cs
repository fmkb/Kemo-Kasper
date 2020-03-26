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
    public Button comicsButton;

    public GameObject loginRegisterCanvas;
    public GameObject loginCanvas;
    public GameObject registerCanvas;
    public GameObject mainCanvas;
    public GameObject comicsCanvas;

    private LoginUser loginScript;
    private bool isLoggedIn;

    void Start()
    {
        GoBackToMain();
        SwitchToLoginTab();
        mainCanvas.gameObject.SetActive(true);
        goBackButton.gameObject.SetActive(false);

        loginButton.onClick.AddListener(OpenLoginRegisterCanvas);
        loginTabButton.onClick.AddListener(SwitchToLoginTab);
        registerTabButton.onClick.AddListener(SwitchToRegisterTab);
        goBackButton.onClick.AddListener(GoBackToMain);
        playNoLoginButton.onClick.AddListener(OpenMainMenuNoLogin);
        comicsButton.onClick.AddListener(OpenComicsCanvas);

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
            comicsCanvas.gameObject.SetActive(false);
            SceneManager.LoadScene("MainMenuLogin");
        }
        else
        {
            loginRegisterCanvas.gameObject.SetActive(true);
            goBackButton.gameObject.SetActive(true);
        }
    }

    public void OpenComicsCanvas()
    {
        comicsCanvas.gameObject.SetActive(true);
        goBackButton.gameObject.SetActive(true);
    }

    public void SwitchToLoginTab()
    {
        this.GetComponent<RegisterUser>().RemoveWarnings();
        this.GetComponent<RegisterUser>().EmptyFields();
        registerCanvas.gameObject.SetActive(false);
        loginCanvas.gameObject.SetActive(true);
        loginTabButton.GetComponent<Image>().color = new Color32(0, 255, 25, 50);
        registerTabButton.GetComponent<Image>().color = new Color32(255, 25, 0, 50);
    }

    public void SwitchToRegisterTab()
    {
        this.GetComponent<LoginUser>().RemoveWarning();
        this.GetComponent<LoginUser>().EmptyFields();
        registerCanvas.gameObject.SetActive(true);
        loginCanvas.gameObject.SetActive(false);
        registerTabButton.GetComponent<Image>().color = new Color32(0, 255, 25, 50);
        loginTabButton.GetComponent<Image>().color = new Color32(255, 25, 0, 50);
    }

    public void GoBackToMain()
    {
        this.GetComponent<LoginUser>().RemoveWarning();
        this.GetComponent<LoginUser>().EmptyFields();
        this.GetComponent<RegisterUser>().RemoveWarnings();
        this.GetComponent<RegisterUser>().EmptyFields();
        SwitchToLoginTab();
        loginRegisterCanvas.gameObject.SetActive(false);
        comicsCanvas.gameObject.SetActive(false);
        goBackButton.gameObject.SetActive(false);
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
