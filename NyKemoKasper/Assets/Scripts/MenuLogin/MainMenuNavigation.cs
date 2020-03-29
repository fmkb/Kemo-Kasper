using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;

public class MainMenuNavigation : MonoBehaviour
{
    public Button game1Button;
    public Button game2Button;
    public Button highscoresButton;
    public Button eventsButton;
    public Button profileButton;
    public Button profileSettingsButton;
    public Button hamburgerButton;

    public Button goBackButton;
    public Button goToLoginMenuButton;

    public GameObject mainCanvas;
    public GameObject game1LaunchCanvas;
    public GameObject game2LaunchCanvas;
    public GameObject higscoresCanvas;
    public GameObject eventsCanvas;
    public GameObject profileCanvas;
    public GameObject hamburger;

    void Start()
    {
        DisableAllCanvas();
        mainCanvas.gameObject.SetActive(true);

        goBackButton.onClick.AddListener(DisableAllCanvas);
        game1Button.onClick.AddListener(OpenGame1LaunchCanvas);
        game2Button.onClick.AddListener(OpenGame2LaunchCanvas);
        highscoresButton.onClick.AddListener(OpenHigscoresCanvas);
        eventsButton.onClick.AddListener(OpenEventCanvas);
        profileButton.onClick.AddListener(OpenProfileCanvas);
        goToLoginMenuButton.onClick.AddListener(GoBackToLoginMenu);
        hamburgerButton.onClick.AddListener(OpenHamburger);
    }

    public void DisableAllCanvas()
    {
        game1LaunchCanvas.gameObject.SetActive(false);
        game2LaunchCanvas.gameObject.SetActive(false);
        higscoresCanvas.gameObject.SetActive(false);
        eventsCanvas.gameObject.SetActive(false);
        profileCanvas.gameObject.SetActive(false);
        goBackButton.gameObject.SetActive(false);
        goToLoginMenuButton.gameObject.SetActive(true);
        hamburger.gameObject.SetActive(false);
        hamburgerButton.gameObject.SetActive(true);
        this.GetComponent<LogOut>().logOut.gameObject.SetActive(true);
    }

    public void OpenGame1LaunchCanvas()
    {
        game1LaunchCanvas.gameObject.SetActive(true);
        goBackButton.gameObject.SetActive(true);
        goToLoginMenuButton.gameObject.SetActive(false);
        hamburgerButton.gameObject.SetActive(false);
        this.GetComponent<LogOut>().logOut.gameObject.SetActive(false);
    }

    public void OpenGame2LaunchCanvas()
    {
        game2LaunchCanvas.gameObject.SetActive(true);
        goBackButton.gameObject.SetActive(true);
        goToLoginMenuButton.gameObject.SetActive(false);
        hamburgerButton.gameObject.SetActive(false);
        this.GetComponent<LogOut>().logOut.gameObject.SetActive(false);
    }

    public void OpenHigscoresCanvas()
    {
        higscoresCanvas.gameObject.SetActive(true);
        goBackButton.gameObject.SetActive(true);
        goToLoginMenuButton.gameObject.SetActive(false);
        hamburgerButton.gameObject.SetActive(false);
        this.GetComponent<LogOut>().logOut.gameObject.SetActive(false);
    }
    
    public void OpenEventCanvas()
    {
        eventsCanvas.gameObject.SetActive(true);
        goBackButton.gameObject.SetActive(true);
        goToLoginMenuButton.gameObject.SetActive(false);
        hamburgerButton.gameObject.SetActive(false);
        this.GetComponent<LogOut>().logOut.gameObject.SetActive(false);
    }

    public void OpenProfileCanvas()
    {
        profileCanvas.gameObject.SetActive(true);
        goBackButton.gameObject.SetActive(true);
        goToLoginMenuButton.gameObject.SetActive(false);
        hamburgerButton.gameObject.SetActive(false);
        this.GetComponent<LogOut>().logOut.gameObject.SetActive(false);
    }

    public void GoBackToLoginMenu()
    {
        mainCanvas.gameObject.SetActive(false);
        SceneManager.LoadScene("LoginMenu");
    }

    public void OpenHamburger()
    {
        hamburger.SetActive(!hamburger.activeInHierarchy);
    }
}
