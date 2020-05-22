using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;

public class MainMenuNoLoginNavigation : MonoBehaviour
{
    public Button game1Button;
    public Button game2Button;
    public Button highscoresButton;
    public Button hamburgerButton;

    public Button goBackButton;
    public Button goToLoginMenuButton;

    public GameObject mainCanvas;
    public GameObject game2LaunchCanvas;
    public GameObject higscoresCanvas;
    public GameObject hamburger;

    void Start()
    {
        DisableAllCanvas();
        mainCanvas.gameObject.SetActive(true);

        goBackButton.onClick.AddListener(DisableAllCanvas);
        game1Button.onClick.AddListener(OpenGame1);
        game2Button.onClick.AddListener(OpenGame2LaunchCanvas);
        highscoresButton.onClick.AddListener(OpenHigscoresCanvas);
        goToLoginMenuButton.onClick.AddListener(GoBackToLoginMenu);
        hamburgerButton.onClick.AddListener(OpenHamburger);
    }

    public void DisableAllCanvas()
    {
        game2LaunchCanvas.gameObject.SetActive(false);
        higscoresCanvas.gameObject.SetActive(false);
        hamburger.gameObject.SetActive(false);
        hamburgerButton.gameObject.SetActive(true);
        HideGoBackButton();
    }

    public void OpenGame1()
    {
        SceneManager.LoadScene("Game1");
    }

    public void OpenGame2LaunchCanvas()
    {
        game2LaunchCanvas.gameObject.SetActive(true);
        ShowGoBackButton();
        hamburgerButton.gameObject.SetActive(false);
    }

    public void OpenHigscoresCanvas()
    {
        higscoresCanvas.gameObject.SetActive(true);
        ShowGoBackButton();
        hamburgerButton.gameObject.SetActive(false);
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

    public void HideGoBackButton()
    {
        goBackButton.gameObject.SetActive(false);
    }

    public void ShowGoBackButton()
    {
        goBackButton.gameObject.SetActive(true);
    }
}
