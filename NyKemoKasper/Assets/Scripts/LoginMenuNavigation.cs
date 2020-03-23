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

    void Start()
    {
        GoBackToMain();
        SwitchToLoginTab();
        mainCanvas.gameObject.SetActive(true);

        loginButton.onClick.AddListener(OpenLoginRegisterCanvas);
        loginTabButton.onClick.AddListener(SwitchToLoginTab);
        registerTabButton.onClick.AddListener(SwitchToRegisterTab);
        goBackButton.onClick.AddListener(GoBackToMain);
        continueButton.onClick.AddListener(OpenMainMenu);
        playNoLoginButton.onClick.AddListener(OpenMainMenu);
    }
    
    public void OpenLoginRegisterCanvas()
    {
        loginRegisterCanvas.gameObject.SetActive(true);
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

    public void OpenMainMenu()
    {
        if(loginCanvas.activeInHierarchy)
        {
            GoBackToMain();
            mainCanvas.gameObject.SetActive(false);
            SceneManager.LoadScene("MainMenuLogin");
        }
        else
        {
            GoBackToMain();
            mainCanvas.gameObject.SetActive(false);
            SceneManager.LoadScene("MainMenuNoLogin");
        }
    }
}
