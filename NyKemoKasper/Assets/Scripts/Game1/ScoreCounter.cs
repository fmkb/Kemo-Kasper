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
    public GameObject continueButton;
    public GameObject bonusPointsScreen;

    private Vector3 defaultBonusPos;
    float countSpeed;


    void Start()
    {
        gameManager = FindObjectOfType<GameManager>();
        timeStamps = new int[gameManager.amountForBonus];
        continueButton.SetActive(false);
        bonusPointsScreen.SetActive(false);
        index = 0;
        countSpeed = 5.0f;

        startTime = System.DateTime.UtcNow;
        totalNormalScore = 0;
        totalBonusScore = 0;
        numberCellsKilledPlayer = 0;
        numberCellsKilledKasper = 0;
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
        yield return new WaitForSeconds(1.5f);

        StartCoroutine(GenerateNormalPoints(countSpeed / totalNormalScore / 2.5f));

        while (numberCellsKilledPlayer > 0)
        {
            killedByYou.text = "" + (int.Parse(killedByYou.text) + 1);
            numberCellsKilledPlayer--;
            yield return new WaitForSeconds(speed);
        }

        yield return new WaitForSeconds(1);

        if (numberCellsKilledKasper > 0)
        {
            StartCoroutine(GenerateKilledKasper(countSpeed / numberCellsKilledKasper));
            totalNormalScore = (int)((numberCellsKilledKasper + 1) * 2 / 3f * gameManager.normalPoints);
            StartCoroutine(GenerateNormalPoints(countSpeed / totalNormalScore / 2.5f));
        }
        else if (totalBonusScore > 0)
        {
            StartCoroutine(GenerateBonusPoints(countSpeed / totalBonusScore / 2.5f));
        }
        else
        {
            continueButton.SetActive(true);
        }
    }

    private IEnumerator GenerateNormalPoints(float speed)
    {
        while (totalNormalScore > 0)
        {
            yourScore.text = "" + (int.Parse(yourScore.text) + 1);
            totalNormalScore--;
            yield return new WaitForSeconds(speed);
        }
    }

    private IEnumerator GenerateKilledKasper(float speed)
    {
        while (numberCellsKilledKasper > 0)
        {
            killedByKasper.text = "" + (int.Parse(killedByKasper.text) + 1);
            numberCellsKilledKasper--;
            yield return new WaitForSeconds(speed);
        }

        yield return new WaitForSeconds(1);

        if (totalBonusScore > 0)
        {
            StartCoroutine(GenerateBonusPoints(countSpeed / totalBonusScore / 2.5f));
        }
        else
        {
            continueButton.SetActive(true);
        }
    }

    private IEnumerator GenerateBonusPoints(float speed)
    {
        bonusPointsScreen.transform.position = defaultBonusPos;
        bonusPointsScreen.SetActive(true);
        bonusPointsScreen.GetComponent<Animator>().Play("ButtonAppear");
        while (totalBonusScore > 0)
        {
            bonusPointsScreen.transform.GetChild(0).GetComponent<Text>().text =
                "" + (int.Parse(bonusPointsScreen.transform.GetChild(0).GetComponent<Text>().text) + 1);
            yourScore.text = "" + (int.Parse(yourScore.text) + 1);
            totalBonusScore--;
            yield return new WaitForSeconds(speed);
        }
        yield return new WaitForSeconds(0.5f);
        bonusPointsScreen.GetComponent<Animator>().Play("TotalBonusDisappear");
        yield return new WaitForSeconds(0.5f);
        bonusPointsScreen.SetActive(false);

        continueButton.SetActive(true);
    }
}
