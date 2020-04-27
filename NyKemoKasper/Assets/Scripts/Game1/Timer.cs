using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class Timer : MonoBehaviour
{
    private GameManager gameManager;
    private int roundTime;

    public Text timeText;
    public Image timeBar;

    void Start()
    {
        gameManager = FindObjectOfType<GameManager>();
        roundTime = gameManager.roundTime;
        StartCoroutine("RoundTimer");
    }

    // Update is called once per frame
    void Update()
    {

    }

    public IEnumerator RoundTimer()
    {
        int countdownValue = roundTime;
        float defaultScale = timeBar.rectTransform.localScale.x;
        while (countdownValue >= 0)
        {
            timeText.text = countdownValue + "";
            yield return new WaitForSeconds(1.0f);
            timeBar.rectTransform.position -= new Vector3(283f / roundTime, 0, 0);
            timeBar.rectTransform.localScale -= new Vector3(defaultScale / roundTime, 0, 0);
            countdownValue--;

            if(countdownValue == -1)
            {
                gameManager.DisplayRoundFinished();
            }
        }
    }
}
