using System.Collections;
using System.Collections.Generic;
using System.Linq;
using UnityEngine;

public class CellSpawner : MonoBehaviour
{
    public GameObject greenCellPrefab;
    public GameObject greenCellsParent;
    public List<GameObject> greenCells;

    private int maxNumberOfGreenCells;

    private float timeToSpawn;

    private GameManager gameManager;

    void Start()
    {
        gameManager = FindObjectOfType<GameManager>();
        maxNumberOfGreenCells = gameManager.maxNumberOfGreenCells;
        timeToSpawn = 1;
        greenCells = new List<GameObject>();
        StartCoroutine("GreenCellSpawner");
        StartCoroutine("GreenCellSpawner");
    }

    private void Update()
    {
        greenCells = greenCells.Where(item => item != null).ToList();
    }

    IEnumerator GreenCellSpawner()
    {
        while (true)
        {
            yield return new WaitForSeconds(timeToSpawn);

            Vector3 position = new Vector3(Random.Range(-800.0f, 800.0f), Random.Range(-300.0f, 300.0f), -200);

            if (!IsTooClose(position, greenCells))
            {
                if (greenCells.Count < maxNumberOfGreenCells)
                {
                    GameObject cell = Instantiate(greenCellPrefab, position, Quaternion.identity);
                    greenCells.Add(cell);
                    cell.transform.SetParent(greenCellsParent.transform);
                    if (greenCells.Count < maxNumberOfGreenCells / 2)
                    {
                        timeToSpawn = Random.Range(0.5f, 1.5f);
                    }
                    else if (greenCells.Count < maxNumberOfGreenCells / 3)
                    {
                        timeToSpawn = Random.Range(0.1f, 0.5f);
                    }
                    else if (greenCells.Count < maxNumberOfGreenCells / 4)
                    {
                        timeToSpawn = 0;
                    }
                    else
                    {
                        timeToSpawn = Random.Range(1.0f, 3.0f);
                    }
                }
                else
                {
                    // do nothing
                }
            }
            else
            {
                timeToSpawn = 0;
            }
        }
    }

    bool IsTooClose(Vector3 objectToSpawn, List<GameObject> alreadySpawned)
    {
        foreach (GameObject previousObject in alreadySpawned)
        {
            if (previousObject != null)
            {
                float distanceX = Mathf.Abs(objectToSpawn.x - previousObject.transform.position.x);
                float distanceY = Mathf.Abs(objectToSpawn.y - previousObject.transform.position.y);

                if (distanceX < 150f && distanceY < 100)
                {
                    return true;
                }
            }
        }
        return false;
    }
}
