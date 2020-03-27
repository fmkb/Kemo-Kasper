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
    public GameObject game1LaunchCanvas;
    public GameObject game2LaunchCanvas;
    public GameObject higscoresCanvas;

    void Start()
    {
        DisableAllCanvas();
        mainCanvas.gameObject.SetActive(true);

        goBackButton.onClick.AddListener(DisableAllCanvas);
        game1Button.onClick.AddListener(OpenGame1LaunchCanvas);
        game2Button.onClick.AddListener(OpenGame2LaunchCanvas);
        highscoresButton.onClick.AddListener(OpenHigscoresCanvas);
        goToLoginMenuButton.onClick.AddListener(GoBackToLoginMenu);
    }

    public void DisableAllCanvas()
    {
        game1LaunchCanvas.gameObject.SetActive(false);
        game2LaunchCanvas.gameObject.SetActive(false);
        higscoresCanvas.gameObject.SetActive(false);
        goBackButton.gameObject.SetActive(false);
    }

    public void OpenGame1LaunchCanvas()
    {
        game1LaunchCanvas.gameObject.SetActive(true);
        goBackButton.gameObject.SetActive(true);
    }

    public void OpenGame2LaunchCanvas()
    {
        game2LaunchCanvas.gameObject.SetActive(true);
        goBackButton.gameObject.SetActive(true);
    }

    public void OpenHigscoresCanvas()
    {
        higscoresCanvas.gameObject.SetActive(true);
        goBackButton.gameObject.SetActive(true);
    }

    public void GoBackToLoginMenu()
    {
        mainCanvas.gameObject.SetActive(false);
        SceneManager.LoadScene("LoginMenu");
    }
}
