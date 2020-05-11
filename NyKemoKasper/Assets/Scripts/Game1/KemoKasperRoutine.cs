﻿using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class KemoKasperRoutine : MonoBehaviour
{
    private GameManager gameManager;
    public Button buttonCallKasper;
    public GameObject kasperPrefab;
    public GameObject shockwavePrefab;
    public GameObject pointPrefab;
    private GameObject pointsParent;
    private List<GameObject> points;

    private GameObject kasper;
    private GameObject shockwave;
    private bool isFacingRight;
    private float radius;

    void Start()
    {
        radius = 200;
        isFacingRight = true;
        buttonCallKasper.gameObject.SetActive(false);
        gameManager = FindObjectOfType<GameManager>();
        buttonCallKasper.onClick.AddListener(CallKemoKasper);

        points = new List<GameObject>();
        pointsParent = GameObject.Find("Points");
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
        int minimumNo = 2;
        for (int i = 0; i < counter; i++)
        {
            Vector3 position = new Vector3(Random.Range(-800.0f, 800.0f), Random.Range(-500.0f, 300.0f), -200);

            if(counter < 10)
            {
                minimumNo = 3;
            } else if(counter < 20)
            {
                minimumNo = 2;
            } else if(counter < 30)
            {
                minimumNo = 1;
            }

            if (gameManager.HowManyGreenCellsInPosition(position, radius) >= minimumNo)
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

    public void ShowPoints(int number)
    {
        GameObject point = null;
        if (number > 0)
        {
            point = Instantiate(pointPrefab, kasper.transform.position, Quaternion.identity);
            point.transform.GetChild(0).GetChild(0).GetComponent<TextMesh>().text = "" + number*2/3f*gameManager.normalPoints;
            points.Add(point);
            point.transform.SetParent(pointsParent.transform);
            Destroy(point, 1.15f);
        }
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
        yield return new WaitForSeconds(0.5f);
        if (kasper != null)
        {
            gameManager.DestroyGreenCellsInRadius(kasper.transform.position, radius);
            shockwave = Instantiate(shockwavePrefab, kasper.transform.position, Quaternion.identity);
            Destroy(shockwave, 21 / 60f);
        }
        yield return null;
    }

    public void DisableKasper()
    {
        Destroy(kasper);
        Destroy(shockwave);
        StopCoroutine("KasperRoutine");
        isFacingRight = true;
    }
}