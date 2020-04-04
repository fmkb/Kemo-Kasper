using UnityEngine;
using UnityEngine.UI;

public class HighscoresLoader : MonoBehaviour
{
    public Button game1TabButton;
    public Button game2TabButton;

    public GameObject game1Tab;
    public GameObject game2Tab;

    public Button[] highscore1Buttons;
    public Button[] highscore2Buttons;

    public GameObject[] highscores1Canvas;
    public GameObject[] highscores2Canvas;

    public Button goBackToHButton;

    void Start()
    {
        goBackToHButton.gameObject.SetActive(false);
        SwitchToGame1Tab();

        goBackToHButton.onClick.AddListener(GoBackToH);
        game1TabButton.onClick.AddListener(SwitchToGame1Tab);
        game2TabButton.onClick.AddListener(SwitchToGame2Tab);

        for (int i = 0; i < highscore1Buttons.Length; i++)
        {
            int tempI = i;
            highscore1Buttons[i].onClick.AddListener(delegate { OpenSpecificHighscore(1, tempI); });
        }

        for (int i = 0; i < highscore2Buttons.Length; i++)
        {
            int tempI = i;
            highscore2Buttons[i].onClick.AddListener(delegate { OpenSpecificHighscore(2, tempI); });
        }
    }

    void GoBackToH()
    {
        CloseAllTabs();
        goBackToHButton.gameObject.SetActive(false);
        this.gameObject.GetComponent<MainMenuNavigation>().goBackButton.gameObject.SetActive(true);
    }

    void SwitchToGame1Tab()
    {
        game1Tab.SetActive(true);
        game2Tab.SetActive(false);
        game1TabButton.GetComponent<Image>().color = new Color32(0, 255, 25, 50);
        game2TabButton.GetComponent<Image>().color = new Color32(255, 25, 0, 50);
    }

    void SwitchToGame2Tab()
    {
        game1Tab.SetActive(false);
        game2Tab.SetActive(true);
        game1TabButton.GetComponent<Image>().color = new Color32(255, 25, 0, 50);
        game2TabButton.GetComponent<Image>().color = new Color32(0, 255, 25, 50);
    }

    void CloseAllTabs()
    {
        foreach (GameObject tab in highscores1Canvas)
        {
            tab.SetActive(false);
        }

        foreach (GameObject tab in highscores2Canvas)
        {
            tab.SetActive(false);
        }
    }

    void OpenSpecificHighscore(int highscoresNo, int tabNo)
    {
        if (highscoresNo == 1)
        {
            highscores1Canvas[tabNo].SetActive(true);
        }

        if (highscoresNo == 2)
        {
            highscores2Canvas[tabNo].SetActive(true);
        }
        
        this.gameObject.GetComponent<MainMenuNavigation>().goBackButton.gameObject.SetActive(false);
        goBackToHButton.gameObject.SetActive(true);
    }
}
