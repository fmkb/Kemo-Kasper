using System.Collections;
using System.Collections.Generic;
using System.Linq;
using UnityEngine;

public class CellSpawner : MonoBehaviour
{
    public GameObject greenCellPrefab;
    public GameObject bonusCellPrefab;
    public GameObject timeCellPrefab;
    public GameObject spawnedCellsParent;
    public List<GameObject> greenCells;
    public List<GameObject> otherCells;

    private int maxNumberOfGreenCells, maxNumberOfOtherCells;
    private int initialNumberOfCells;
    private int greenCellNo, otherCellNo;

    private GameManager gameManager;

    private bool spawningEnabled;

    void Start()
    {
        greenCellNo = 0;
        otherCellNo = 0;
        gameManager = FindObjectOfType<GameManager>();
        maxNumberOfGreenCells = gameManager.maxNumberOfGreenCells;
        maxNumberOfOtherCells = gameManager.maxNumberOfOtherCells;
        greenCells = new List<GameObject>();
        otherCells = new List<GameObject>();
        StartCoroutine("GreenCellsSpawner");
        StartCoroutine("GreenCellsSpawner");
        spawningEnabled = true;
        SpawnInitialGreenCells();
    }

    private void Update()
    {
        greenCells = greenCells.Where(item => item != null).ToList();
        otherCells = otherCells.Where(item => item != null).ToList();
    }

    private void SpawnInitialGreenCells()
    {
        initialNumberOfCells = gameManager.initialNumberOfGreenCells;
        for (int i = 0; i < initialNumberOfCells; i++)
        {
            Vector3 position = new Vector3(Random.Range(-800.0f, 800.0f), Random.Range(-300.0f, 300.0f), -200);
            if (!IsTooClose(position, greenCells))
            {
                if (GetNumberOfGreenCells() < maxNumberOfGreenCells)
                {
                    if (spawningEnabled)
                    {
                        GameObject cell = Instantiate(greenCellPrefab, position, Quaternion.identity);
                        cell.name = "Enemy_" + greenCellNo;
                        greenCellNo++;
                        greenCells.Add(cell);
                        cell.transform.SetParent(spawnedCellsParent.transform);
                    }
                }
            }
            else
            {
                initialNumberOfCells++;
            }
        }
    }

    IEnumerator OtherCellsSpawner()
    {
        float timeToSpawn = 1;
        bool isBonusCellNext = true;

        while (true)
        {
            yield return new WaitForSeconds(timeToSpawn);

            Vector3 position = new Vector3(Random.Range(-800.0f, 800.0f), Random.Range(-300.0f, 300.0f), -200);

            if (!IsTooClose(position, otherCells))
            {
                if (GetNumberOfOtherCells() < maxNumberOfOtherCells)
                {
                    if (spawningEnabled)
                    {
                        GameObject cell = null;
                        if (isBonusCellNext)
                        {
                            cell = Instantiate(bonusCellPrefab, position, Quaternion.identity);
                            cell.name = "BonusCell_" + otherCellNo;
                            isBonusCellNext = false;
                        }
                        else 
                        {
                            cell = Instantiate(timeCellPrefab, position, Quaternion.identity);
                            cell.name = "TimeCell_" + otherCellNo;
                            otherCellNo++;
                            isBonusCellNext = true;
                        }
                        otherCells.Add(cell);
                        cell.transform.SetParent(spawnedCellsParent.transform);
                    }
                    if (GetNumberOfOtherCells() < maxNumberOfOtherCells / 4)
                    {
                        timeToSpawn = 0.75f;
                    }
                    else if (GetNumberOfOtherCells() < maxNumberOfOtherCells / 3)
                    {
                        timeToSpawn = Random.Range(1.0f, 2.0f);
                    }
                    else if (GetNumberOfOtherCells() < maxNumberOfOtherCells / 2)
                    {
                        timeToSpawn = Random.Range(2.0f, 3.0f);
                    }
                    else
                    {
                        timeToSpawn = Random.Range(3.0f, 5.0f);
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

    IEnumerator GreenCellsSpawner()
    {
        float timeToSpawn = 1;

        while (true)
        {
            yield return new WaitForSeconds(timeToSpawn);
            
            Vector3 position = new Vector3(Random.Range(-800.0f, 800.0f), Random.Range(-300.0f, 300.0f), -200);

            if (!IsTooClose(position, greenCells))
            {
                if (GetNumberOfGreenCells() < maxNumberOfGreenCells)
                {
                    if (spawningEnabled)
                    {
                        GameObject cell = Instantiate(greenCellPrefab, position, Quaternion.identity);
                        cell.name = "Enemy_" + greenCellNo;
                        greenCellNo++;
                        greenCells.Add(cell);
                        cell.transform.SetParent(spawnedCellsParent.transform);
                    }
                    if (GetNumberOfGreenCells() < maxNumberOfGreenCells / 4)
                    {
                        timeToSpawn = 0;
                    }
                    else if (GetNumberOfGreenCells() < maxNumberOfGreenCells / 3)
                    {
                        timeToSpawn = Random.Range(0.1f, 0.5f);
                    }
                    else if (GetNumberOfGreenCells() < maxNumberOfGreenCells / 2)
                    {
                        timeToSpawn = Random.Range(0.5f, 1.5f);
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

    public bool IsTooClose(Vector3 objectToSpawn, List<GameObject> alreadySpawned)
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

    public int HowManyCellsInRadius(Vector3 position, float radius)
    {
        int number = 0;
        foreach (GameObject greenCell in greenCells)
        {
            if (greenCell != null)
            {
                float distanceX = Mathf.Abs(position.x - greenCell.transform.position.x);
                float distanceY = Mathf.Abs(position.y - greenCell.transform.position.y - 100);

                if (distanceX < radius && distanceY < radius)
                {
                    number++;
                }
            }
        }
        return number;
    }

    public int GetNumberOfGreenCells()
    {
        return greenCells.Count;
    }

    public int GetNumberOfOtherCells()
    {
        return otherCells.Count;
    }

    public void ReplicateGreenCell(Transform origin)
    {
        Vector3 position = new Vector3(origin.position.x + 35, origin.position.y, origin.position.z);
        GameObject replica = Instantiate(greenCellPrefab, position, Quaternion.identity);
        replica.GetComponent<GreenCellRoutine>().isInstantiatedInReplication = true;
        replica.name = "Enemy_" + greenCellNo;
        greenCells.Add(replica);
        replica.transform.SetParent(spawnedCellsParent.transform);
    }

    public void KillAllTheCells()
    {
        spawningEnabled = false;
        foreach (GameObject cell in greenCells)
        {
            Destroy(cell);
        }
    }

    public void KillCellsInRadius(Vector3 position, float radius)
    {
        int numberKilled = 0;
        foreach (GameObject greenCell in greenCells)
        {
            if (greenCell != null)
            {
                float distanceX = Mathf.Abs(greenCell.transform.position.x - position.x);
                float distanceY = Mathf.Abs(greenCell.transform.position.y - position.y);

                if (distanceX < radius + 100 && distanceY < radius + 50)
                {
                    greenCell.GetComponent<GreenCellRoutine>().ZeroSpeed();
                    greenCell.GetComponent<GreenCellsClicker>().wasCellClicked = true;
                    greenCell.GetComponent<Animator>().gameObject.SetActive(false);
                    greenCell.GetComponent<Animator>().gameObject.SetActive(true);
                    greenCell.GetComponent<Animator>().Play("GreenCellExplode");
                    Destroy(greenCell, 1 / 6f);
                    numberKilled++;
                }
            }
        }
        foreach (GameObject otherCell in otherCells)
        {
            if (otherCell != null)
            {
                float distanceX = Mathf.Abs(otherCell.transform.position.x - position.x);
                float distanceY = Mathf.Abs(otherCell.transform.position.y - position.y);

                if (distanceX < radius + 100 && distanceY < radius + 50)
                {
                    otherCell.GetComponent<Animator>().gameObject.SetActive(false);
                    otherCell.GetComponent<Animator>().gameObject.SetActive(true);
                    otherCell.GetComponent<Animator>().Play("OtherCellExplode");
                    Destroy(otherCell, 1 / 6f);
                    numberKilled++;
                }
            }
        }
        gameManager.DisplayKasperPoints(numberKilled);
    }

    public void StartSpawningOtherCells()
    {
        StartCoroutine("OtherCellsSpawner");
    }

    public void StartSpawningMoreGreenCells()
    {
        StartCoroutine("GreenCellsSpawner");
    }

    public void ReenableSpawning()
    {
        greenCellNo = 0;
        spawningEnabled = true;
        SpawnInitialGreenCells();
    }
}
