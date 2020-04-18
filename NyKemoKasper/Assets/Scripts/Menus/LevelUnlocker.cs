using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;

public class LevelUnlocker : MonoBehaviour
{
    public Button[] levelButtons;
    public int highestUnlockedLVL;

    private Color32 unlockedColour;

    void Start()
    {
        unlockedColour = new Color32(80, 255, 90, 255);
        GetHighestUnlockedLVL();
        DisableAllButtons();
        UnlockLevels();
    }
    
    void GetHighestUnlockedLVL()
    {
        if(SceneManager.GetActiveScene().name == "MainMenuLogin")
        {
            if(this.name == "Game1Launch")
            {
                if (PlayerPrefs.HasKey("Game1HighestLVLLogin"))
                {
                    highestUnlockedLVL = PlayerPrefs.GetInt("Game1HighestLVLLogin");
                }
                else highestUnlockedLVL = 1;
            }

            if (this.name == "Game2Launch")
            {
                if (PlayerPrefs.HasKey("Game1HighestLVLLogin"))
                {
                    highestUnlockedLVL = PlayerPrefs.GetInt("Game2HighestLVLLogin");
                }
                else highestUnlockedLVL = 1;
            }
        }
        
        if (SceneManager.GetActiveScene().name == "MainMenuNoLogin")
        {
            if (this.name == "Game1Launch")
            {
                if (PlayerPrefs.HasKey("Game1HighestLVLLogin"))
                {
                    highestUnlockedLVL = PlayerPrefs.GetInt("Game1HighestLVLNoLogin");
                }
                else highestUnlockedLVL = 1;
            }

            if (this.name == "Game2Launch")
            {
                if (PlayerPrefs.HasKey("Game1HighestLVLLogin"))
                {
                    highestUnlockedLVL = PlayerPrefs.GetInt("Game2HighestLVLNoLogin");
                }
                else highestUnlockedLVL = 1;
            }
        }
    }

    void UnlockLevels()
    {
        for(int i = 0; i < (highestUnlockedLVL-2); i++)
        {
            levelButtons[i].GetComponent<Image>().color = unlockedColour;
            levelButtons[i].interactable = true;
            levelButtons[i].transform.GetChild(0).gameObject.SetActive(true);
            levelButtons[i].transform.GetChild(1).gameObject.SetActive(false);
        }
    }

    void DisableAllButtons()
    {
        foreach(Button button in levelButtons)
        {
            button.interactable = false;
            button.transform.GetChild(0).gameObject.SetActive(false);
        }
    }

    
}
