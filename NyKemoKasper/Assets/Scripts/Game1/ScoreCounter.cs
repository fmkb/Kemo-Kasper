using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class ScoreCounter : MonoBehaviour
{
    public System.DateTime startTime;
    private int time;
    public int[] timeStamps;
    private int index;

    public int totalNormalScore;
    public int totalBonusScore;
    public int numberCellsKilled;

    private GameManager gameManager;

    void Start()
    {
        gameManager = FindObjectOfType<GameManager>();
        timeStamps = new int[gameManager.amountForBonus];
        index = 0;

        startTime = System.DateTime.UtcNow;
        totalNormalScore = 0;
        totalBonusScore = 0;
        numberCellsKilled = 0;
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

        numberCellsKilled++;

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
}
