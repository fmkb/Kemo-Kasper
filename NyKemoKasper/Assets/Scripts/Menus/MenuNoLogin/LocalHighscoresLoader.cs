using UnityEngine;
using UnityEngine.UI;

public class LocalHighscoresLoader : MonoBehaviour
{
    public Button game1tabButton, game2tabButton;

    public Button nextButton, backButton;

    public GameObject board;

    private int currentPage, currentGame;



    void Start()
    {
        SwitchToGame1Tab();

        nextButton.onClick.AddListener(NextPage);
        backButton.onClick.AddListener(PreviousPage);
        game1tabButton.onClick.AddListener(SwitchToGame1Tab);
        game2tabButton.onClick.AddListener(SwitchToGame2Tab);
    }
    
    void NextPage()
    {
        if(currentPage < 4)
        {
            nextButton.gameObject.SetActive(true);
            currentPage++;

            WriteHighscore();

            if (currentPage == 4)
            {
                nextButton.gameObject.SetActive(false);
            }
            if(currentPage > 0)
            {
                backButton.gameObject.SetActive(true);
            }
        }
    }

    void PreviousPage()
    {
        if (currentPage > 0)
        {
            backButton.gameObject.SetActive(true);
            currentPage--;

            WriteHighscore();

            if (currentPage == 0)
            {
                backButton.gameObject.SetActive(false);
            }
            if(currentPage < 4)
            {
                nextButton.gameObject.SetActive(true);
            }
        }
    }

    void SwitchToGame1Tab()
    {
        game1tabButton.GetComponent<Image>().color = new Color32(0, 255, 25, 50);
        game2tabButton.GetComponent<Image>().color = new Color32(255, 25, 0, 50);

        currentGame = 1;
        currentPage = 0;

        WriteHighscore();
        backButton.gameObject.SetActive(false);
    }

    void SwitchToGame2Tab()
    {
        game1tabButton.GetComponent<Image>().color = new Color32(255, 25, 0, 50);
        game2tabButton.GetComponent<Image>().color = new Color32(0, 255, 25, 50);
        
        currentGame = 2;
        currentPage = 0;

        WriteHighscore();
        backButton.gameObject.SetActive(false);
    }

    void WriteHighscore()
    {
        board.transform.GetChild(0).GetComponent<Text>().text = "";
        board.transform.GetChild(1).GetComponent<Text>().text = "";
        for (int i = currentPage * 10; i < currentPage * 10 + 10; i++)
        {
            if (PlayerPrefs.GetInt("Game" + currentGame + "HNL" + i) > 0)
            {
                board.transform.GetChild(0).GetComponent<Text>().text += PlayerPrefs.GetString("Game" + currentGame + "HNLN" + i) + "\n";
                board.transform.GetChild(1).GetComponent<Text>().text += PlayerPrefs.GetInt("Game" + currentGame + "HNL" + i) + "\n";
            }
            else
            {
                board.transform.GetChild(0).GetComponent<Text>().text += "-" + "\n";
                board.transform.GetChild(1).GetComponent<Text>().text += "-" + "\n";
            }
        }
    }
}
