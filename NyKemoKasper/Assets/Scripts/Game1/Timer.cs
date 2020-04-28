using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class Timer : MonoBehaviour
{
    private GameManager gameManager;
    private int roundTime;
    private int countdownValue;

    public Text timeText;
    public Image timeBar;
    Vector3 defaultPosition;
    Vector3 defaultScale;

    public GameObject clockIcon;

    void Start()
    {
        gameManager = FindObjectOfType<GameManager>();
        roundTime = gameManager.roundTime;

        defaultPosition = timeBar.rectTransform.position;
        defaultScale = timeBar.rectTransform.localScale;

        StartCoroutine("RoundTimer");
        StartCoroutine("MoveTimeBar");
    }
    
    void Update()
    {
    }

    public IEnumerator RoundTimer()
    {
        countdownValue = roundTime;
        while (countdownValue >= 0)
        {
            timeText.text = countdownValue + "";
            yield return new WaitForSeconds(1.0f);

            countdownValue--;

            if(countdownValue == -1)
            {
                gameManager.DisplayRoundFinished();
            }
        }
    }

    IEnumerator MoveTimeBar()
    {
        float elapsedTime = -1;

        while (elapsedTime <= roundTime)
        {
            timeBar.rectTransform.position = Vector3.Lerp(timeBar.rectTransform.position,
            new Vector3(timeBar.rectTransform.position.x - 283f / roundTime, defaultPosition.y, defaultPosition.z),
            (Time.deltaTime));

            if (countdownValue > 0)
            {
                timeBar.rectTransform.localScale = Vector3.Lerp(timeBar.rectTransform.localScale,
                    new Vector3((timeBar.rectTransform.localScale.x * (1f - 1f / countdownValue)), defaultScale.y, defaultScale.z),
                    (Time.deltaTime));
            }

            if (countdownValue < 6)
            {
                clockIcon.transform.localScale = Vector3.Lerp(clockIcon.transform.localScale,
                    new Vector3((Mathf.Cos(5 * Time.time) + 2) / 1.7f, (Mathf.Cos(5 * Time.time) + 2) / 1.7f, (Mathf.Cos(5 * Time.time) + 2) / 1.7f),
                    Time.deltaTime);
            }

            elapsedTime += Time.deltaTime;
            
            yield return null;
        }
        yield return null;
    }
}
