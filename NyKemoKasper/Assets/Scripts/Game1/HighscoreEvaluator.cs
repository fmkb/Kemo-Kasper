using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class HighscoreEvaluator : MonoBehaviour
{
    private int[] top50scores;
    private string[] top50names;
    private int newIndex;

    private int newScore;
    private string newName;

    public GameObject scoreNameScreen;
    public InputField nameField;
    public Text highscore;

    public GameObject newHighscoreObj;
    public Button addScoreButton, saveNameButton;

    void Start()
    {
        scoreNameScreen.SetActive(false);
        newHighscoreObj.SetActive(false);
        top50scores = new int[50];
        top50names = new string[50];
        //RemoveHighscores();
        highscore.text = "" + PlayerPrefs.GetInt("Game1HNL0");

        addScoreButton.onClick.AddListener(OpenNameField);
        saveNameButton.onClick.AddListener(PutNewScoreIn);
    }
    
    private void GetTop50Scores()
    {
        for(int i = 0; i < 50; i++)
        {
            top50scores[i] = PlayerPrefs.GetInt("Game1HNL" + i);
            top50names[i] = PlayerPrefs.GetString("Game1HNLN" + i);
        }
    }

    public bool CheckIfScoreInTop50(int score)
    {
        GetTop50Scores();
        for(int i = 0; i < 50; i++)
        {
            if (score > top50scores[i])
            {
                if(i == 0)
                {
                    StartCoroutine("DisplayNewHighscore");
                }
                newIndex = i;
                newScore = score;
                return true;
            }
        }
        return false;
    }

    private void PutNewScoreIn()
    {
        if (nameField.text.Length > 0)
        {
            newName = nameField.text;
            GetTop50Scores();
            for (int i = 49; i > newIndex; i--)
            {
                if (i > 0)
                {
                    top50scores[i] = top50scores[i - 1];
                    top50names[i] = top50names[i - 1];
                    PlayerPrefs.SetInt("Game1HNL" + i, top50scores[i - 1]);
                    PlayerPrefs.SetString("Game1HNLN" + i, top50names[i - 1]);
                }
                if (i == 1)
                {
                    highscore.text = "" + newScore;
                }
            }
            top50scores[newIndex] = newScore;
            top50names[newIndex] = newName;

            Debug.Log("Saving " + newScore + ",  " + newName + "    as the score at " + newIndex);
            PlayerPrefs.SetInt("Game1HNL" + newIndex, newScore);
            PlayerPrefs.SetString("Game1HNLN" + newIndex, newName);

            scoreNameScreen.SetActive(false);
        }
    }

    private void OpenNameField()
    {
        scoreNameScreen.SetActive(true);
        nameField.text = "";
        addScoreButton.gameObject.SetActive(false);
    }

    private IEnumerator DisplayNewHighscore()
    {
        newHighscoreObj.SetActive(true);

        yield return new WaitForSeconds(2);

        newHighscoreObj.SetActive(false);
    }

    private void RemoveHighscores()
    {
        for(int i = 0; i < 50; i++)
        {
            PlayerPrefs.SetInt("Game1HNL" + i, 0);
            PlayerPrefs.SetString("Game1HNLN" + i, "");
        }
    }
}
