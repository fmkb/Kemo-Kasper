using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class BonusCellRoutine : MonoBehaviour
{
    public GameObject pointsBonusPrefab;
    private GameObject pointsParent;
    private List<GameObject> points;
    private GameManager gameManager;
    private bool wasBonusUsed;

    void Start()
    {
        gameManager = FindObjectOfType<GameManager>();
        points = new List<GameObject>();
        pointsParent = GameObject.Find("Points");
        wasBonusUsed = false;
    }
    
    void Update()
    {
        
    }

    private void OnTriggerEnter2D(Collider2D other)
    {
        if (other.name == "Pipe")
        {
            if (!wasBonusUsed)
            {
                GetComponent<Animator>().gameObject.SetActive(false);
                GetComponent<Animator>().gameObject.SetActive(true);
                GetComponent<Animator>().Play("OtherCellExplode");
                Destroy(this.gameObject, 1 / 6f);
                ShowPoints();
                wasBonusUsed = true;
            }
        }
    }

    private void ShowPoints()
    {
        Vector3 position = new Vector3(transform.position.x, transform.position.y, transform.position.z);
        GameObject point = null;
        point = Instantiate(pointsBonusPrefab, position, Quaternion.identity);
        point.transform.GetChild(0).GetChild(0).GetComponent<TextMesh>().text = "" + 100;
        points.Add(point);
        point.transform.SetParent(pointsParent.transform);
        gameManager.AddPointsForBonusCell();
        Destroy(point, 1.15f);
    }
}
