﻿using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class GreenCellsClicker : MonoBehaviour
{
    private Collider2D _collider;
    private GameManager gameManager;

    public GameObject pointsNormalPrefab;
    public GameObject pointsBonusPrefab;
    private GameObject pointsParent;
    private List<GameObject> points;
    public bool wasCellClicked;



    void Start()
    {
        _collider = GetComponent<Collider2D>();
        gameManager = FindObjectOfType<GameManager>();
        points = new List<GameObject>();

        pointsParent = GameObject.Find("Points");
        wasCellClicked = false;

        pointsNormalPrefab.transform.GetChild(0).GetChild(0).GetComponent<TextMesh>().text = "" + gameManager.normalPoints;
        pointsBonusPrefab.transform.GetChild(0).GetChild(0).GetComponent<TextMesh>().text = "" + gameManager.bonusPoints;
    }

    void Update()
    {
        if (Input.touchCount > 0 && Input.GetTouch(0).phase == TouchPhase.Began)
        {
            var wp = Camera.main.ScreenToWorldPoint(Input.GetTouch(0).position);
            var touchPosition = new Vector2(wp.x, wp.y);

            if (_collider == Physics2D.OverlapPoint(touchPosition))
            {
                DestroyTheCell();
            }
        }
    }

    private void OnMouseDown()
    {
        DestroyTheCell();
    }

    private void DestroyTheCell()
    {
        if (!gameManager.IsGamePaused())
        {
            if (!wasCellClicked)
            {
                wasCellClicked = true;
                GetComponent<GreenCellRoutine>().ZeroSpeed();
                GetComponent<Animator>().gameObject.SetActive(false);
                GetComponent<Animator>().gameObject.SetActive(true);
                GetComponent<Animator>().Play("GreenCellDestroy");
                Destroy(this.gameObject, 0.45f);
                gameManager.IncreaseStats();
                gameManager.AddTimeStamp();
                ShowPoints();
            }
        }
    }

    private void ShowPoints()
    {
        Vector3 position = new Vector3(transform.position.x, transform.position.y, transform.position.z);
        GameObject point = null;
        if (!gameManager.CheckForBonus())
        {
            point = Instantiate(pointsNormalPrefab, position, Quaternion.identity);
            gameManager.PlayKillCellSound();
        }
        else
        {
            point = Instantiate(pointsBonusPrefab, position, Quaternion.identity);
            gameManager.PlayGetBonusSound();
        }
        points.Add(point);
        point.transform.SetParent(pointsParent.transform);
        Destroy(point, 1.15f);
    }
}
