using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class KemoKasperRoutine : MonoBehaviour
{
    private GameManager gameManager;
    public Button buttonCallKasper;
    public GameObject kasperPrefab;

    private GameObject kasper;
    private bool isFacingRight;

    void Start()
    {
        isFacingRight = true;
        buttonCallKasper.gameObject.SetActive(false);
        gameManager = FindObjectOfType<GameManager>();
        buttonCallKasper.onClick.AddListener(CallKemoKasper);
    }
    
    void Update()
    {
        
    }

    public void TurnOnCallButton()
    {
        StartCoroutine("TurnOnButton");
    }

    IEnumerator TurnOnButton()
    {
        yield return new WaitForSeconds(3);
        buttonCallKasper.gameObject.SetActive(true);
    }

    void CallKemoKasper()
    {
        buttonCallKasper.gameObject.SetActive(false);
        kasper = Instantiate(kasperPrefab, new Vector3(-1000, 0, -200), Quaternion.identity);
        StartCoroutine("KasperRoutine");
    }

    Vector3 GetNewAttackPosition()
    {
        int counter = 1;
        for (int i = 0; i < counter; i++)
        {
            Vector3 position = new Vector3(Random.Range(-800.0f, 800.0f), Random.Range(-500.0f, 300.0f), -200);
            if (CheckForGreenCells(position))
            {
               if(position.y < kasper.transform.position.y)
                {
                    if(isFacingRight)
                    {
                        kasper.GetComponent<SpriteRenderer>().flipX = true;
                        isFacingRight = false;
                    }
                }
                else
                {
                    if(!isFacingRight)
                    {
                        kasper.GetComponent<SpriteRenderer>().flipX = false;
                        isFacingRight = true;
                    }
                }
               return position;
            }
            else
            {
                counter++;
            }
        }
        return Vector3.zero;
    }

    bool CheckForGreenCells(Vector3 position)
    {
        return gameManager.AreGreenCellsInPosition(position);
    }

    IEnumerator KasperRoutine()
    {
        bool firstTime = true;
        while(true)
        {
            if(!firstTime)
            {
                yield return new WaitForSeconds(1.5f);
            }
            StartCoroutine("MoveKasperToPos");
            if(firstTime)
            {
                firstTime = false;
            }
            yield return new WaitForSeconds(1f);
        }
    }

    IEnumerator MoveKasperToPos()
    {
        float elapsedTime = 0;
        Vector3 newPos = GetNewAttackPosition();

        while (elapsedTime <= 1)
        {
            kasper.transform.position = Vector3.Lerp(kasper.transform.position, newPos, Time.deltaTime);

            elapsedTime += Time.deltaTime;

            yield return null;
        }
        yield return null;
    }

    public void DisableKasper()
    {
        Destroy(kasper);
        StopCoroutine("KasperRoutine");
        isFacingRight = true;
    }
}
