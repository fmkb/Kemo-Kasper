using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class ScoreCounter : MonoBehaviour
{
    public System.DateTime startTime;
    private int time;
    public int[] timeStamps;
    private int index;

    public int totalNormalScore;
    public int totalBonusScore;
    public int numberCellsKilledPlayer;
    public int numberCellsKilledKasper;

    private GameManager gameManager;

    public Text yourScore;
    public Text killedByYou;
    public Text killedByKasper;
    public GameObject continueButton, gameFinishedButton, addScoreButton;
    public GameObject bonusPointsScreen;

    private Vector3 defaultBonusPos;
    float countSpeed;

    public bool isTheLastScreen, isScoreBigEnough;



    void Start()
    {
        gameManager = FindObjectOfType<GameManager>();
        timeStamps = new int[gameManager.amountForBonus];
        continueButton.SetActive(false);
        gameFinishedButton.SetActive(false);
        addScoreButton.SetActive(false);
        bonusPointsScreen.SetActive(false);
        index = 0;
        countSpeed = 1.0f;

        defaultBonusPos = bonusPointsScreen.transform.position;
    }
    
    void Update()
    {
        if(Time.timeScale > 0)
        time = (int)(System.DateTime.UtcNow - startTime).TotalMilliseconds;
    }

    public void AddTimeStamp()
    {
        if (index < gameManager.amountForBonus)
        {
            timeStamps[index] = time;
            if (index < gameManager.amountForBonus - 1)
            {
                index++;
            }
        }
    }

    public bool CheckForBonus()
    {
        if (timeStamps[index-1] - timeStamps[0] > gameManager.timeForBonus * 1000)
        {
            timeStamps = new int[gameManager.amountForBonus];
            index = 0;
        }

        numberCellsKilledPlayer++;

        if (index + 1 == gameManager.amountForBonus)
        {
            timeStamps = new int[gameManager.amountForBonus];
            index = 0;
            totalBonusScore += gameManager.bonusPoints;
            return true;
        }

        totalNormalScore += gameManager.normalPoints;
        return false;
    }

    public void GetSummary()
    {
        StartCoroutine(GeneratePlayerKills(countSpeed / numberCellsKilledPlayer));
    }

    private IEnumerator GeneratePlayerKills(float speed)
    {
        yield return new WaitForSeconds(2.5f);

        StartCoroutine(GenerateNormalPoints(countSpeed / totalNormalScore / 20f));

        while (numberCellsKilledPlayer > 0)
        {
            killedByYou.text = "" + (int.Parse(killedByYou.text) + 1);
            numberCellsKilledPlayer--;
            yield return new WaitForSeconds(speed);
        }

        if (numberCellsKilledKasper > 0)
        {
            StartCoroutine(GenerateKilledKasper(countSpeed / numberCellsKilledKasper));
        }
        else if (totalBonusScore > 0)
        {
            StartCoroutine(GenerateBonusPoints(countSpeed / totalBonusScore / 20f));
        }
        else
        {
            while (totalNormalScore > 0 || numberCellsKilledPlayer > 0)
            {
                yield return new WaitForSeconds(1f);
            }

            if (!isTheLastScreen)
            {
                continueButton.SetActive(true);
            }
            else
            {
                if (gameManager.CheckIfScoreInTop50(GetScore()))
                {
                    isScoreBigEnough = true;
                }
                gameFinishedButton.SetActive(true);
                if(isScoreBigEnough)
                {
                    addScoreButton.SetActive(true);
                    isScoreBigEnough = false;
                }
                isTheLastScreen = false;
            }
        }
    }

    private IEnumerator GenerateNormalPoints(float speed)
    {
        gameManager.PlayCountingScoreSoundOn();
        while (totalNormalScore > 0)
        {
            yourScore.text = "" + (int.Parse(yourScore.text) + 5);
            totalNormalScore-=5;
            yield return new WaitForSeconds(speed);
        }
        gameManager.PlayCountingScoreSoundOff();
    }

    private IEnumerator GenerateKilledKasper(float speed)
    {
        while(totalNormalScore > 0 || numberCellsKilledPlayer > 0)
        {
            yield return new WaitForSeconds(1f);
        }

        yield return new WaitForSeconds(0.5f);

        totalNormalScore = (int)((numberCellsKilledKasper + 1) * 2 / 3f * gameManager.normalPoints);
        StartCoroutine(GenerateNormalPoints(countSpeed / totalNormalScore / 20f));

        while (numberCellsKilledKasper > 0)
        {
            killedByKasper.text = "" + (int.Parse(killedByKasper.text) + 1);
            numberCellsKilledKasper--;
            yield return new WaitForSeconds(speed);
        }

        if (totalBonusScore > 0)
        {
            StartCoroutine(GenerateBonusPoints(countSpeed / totalBonusScore / 20f));
        }
        else
        {
            while (totalNormalScore > 0 || numberCellsKilledPlayer > 0)
            {
                yield return new WaitForSeconds(1f);
            }

            if (!isTheLastScreen)
            {
                continueButton.SetActive(true);
            }
            else
            {
                if (gameManager.CheckIfScoreInTop50(GetScore()))
                {
                    isScoreBigEnough = true;
                }
                gameFinishedButton.SetActive(true);
                if (isScoreBigEnough)
                {
                    addScoreButton.SetActive(true);
                    isScoreBigEnough = false;
                }
                isTheLastScreen = false;
            }
        }
    }

    private IEnumerator GenerateBonusPoints(float speed)
    {
        while (totalNormalScore > 0 || numberCellsKilledKasper > 0)
        {
            yield return new WaitForSeconds(1f);
        }

        yield return new WaitForSeconds(0.5f);

        bonusPointsScreen.transform.position = defaultBonusPos;
        bonusPointsScreen.SetActive(true);
        gameManager.PlayBonusScoreAppearSound();
        bonusPointsScreen.GetComponent<Animator>().Play("ButtonAppear");

        gameManager.PlayCountingScoreSoundOn();
        while (totalBonusScore > 0)
        {
            bonusPointsScreen.transform.GetChild(0).GetComponent<Text>().text =
                "" + (int.Parse(bonusPointsScreen.transform.GetChild(0).GetComponent<Text>().text) + 2);
            yourScore.text = "" + (int.Parse(yourScore.text) + 5);
            totalBonusScore-=5;
            yield return new WaitForSeconds(speed / 5);
        }
        gameManager.PlayCountingScoreSoundOff();
        yield return new WaitForSeconds(1f);
        bonusPointsScreen.GetComponent<Animator>().Play("TotalBonusDisappear");
        yield return new WaitForSeconds(0.5f);
        bonusPointsScreen.SetActive(false);


        if (!isTheLastScreen)
        {
            continueButton.SetActive(true);
        }
        else
        {
            if (gameManager.CheckIfScoreInTop50(GetScore()))
            {
                isScoreBigEnough = true;
            }
            gameFinishedButton.SetActive(true);
            if (isScoreBigEnough)
            {
                addScoreButton.SetActive(true);
                isScoreBigEnough = false;
            }
            isTheLastScreen = false;
        }
    }

    public void ZeroOutPoints()
    {
        startTime = System.DateTime.UtcNow;
        totalNormalScore = 0;
        totalBonusScore = 0;
        numberCellsKilledPlayer = 0;
        numberCellsKilledKasper = 0;

        killedByYou.text = "0";
        killedByKasper.text = "0";
        yourScore.text = "0";
        bonusPointsScreen.transform.GetChild(0).GetComponent<Text>().text = "0";
    }

    public int GetScore()
    {
        return int.Parse(yourScore.text);
    }
}
