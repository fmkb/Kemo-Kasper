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

    public Button goBackButton;
    public Button goToLoginMenuButton;

    public GameObject mainCanvas;
    public GameObject game2LaunchCanvas;
    public GameObject higscoresCanvas;

    void Start()
    {
        DisableAllCanvas();
        mainCanvas.gameObject.SetActive(true);

        goBackButton.onClick.AddListener(DisableAllCanvas);
        game1Button.onClick.AddListener(OpenGame1);
        game2Button.onClick.AddListener(OpenGame2LaunchCanvas);
        highscoresButton.onClick.AddListener(OpenHigscoresCanvas);
        //goToLoginMenuButton.onClick.AddListener(GoBackToLoginMenu);
        goToLoginMenuButton.onClick.AddListener(OpenGame2LaunchCanvas);
    }

    public void DisableAllCanvas()
    {
        game2LaunchCanvas.gameObject.SetActive(false);
        higscoresCanvas.gameObject.SetActive(false);
        HideGoBackButton();
        ShowGoToMenuButton();
    }

    public void OpenGame1()
    {
        SceneManager.LoadScene("Game1");
    }

    public void OpenGame2LaunchCanvas()
    {
        game2LaunchCanvas.gameObject.SetActive(true);
        ShowGoBackButton();
        HideGoToMenuButton();
    }

    public void OpenHigscoresCanvas()
    {
        higscoresCanvas.gameObject.SetActive(true);
        ShowGoBackButton();
        HideGoToMenuButton();
    }

    public void GoBackToLoginMenu()
    {
        //mainCanvas.gameObject.SetActive(false);
        //SceneManager.LoadScene("LoginMenu");
    }

    public void HideGoBackButton()
    {
        goBackButton.gameObject.SetActive(false);
    }

    private void HideGoToMenuButton()
    {
        goToLoginMenuButton.gameObject.SetActive(false);
    }

    private void ShowGoToMenuButton()
    {
        goToLoginMenuButton.gameObject.SetActive(true);
    }

    public void ShowGoBackButton()
    {
        goBackButton.gameObject.SetActive(true);
    }
}
