using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class CellSpawner : MonoBehaviour
{
    public GameObject greenCellPrefab;
    public GameObject greenCellsParent;
    public List<GameObject> greenCells;

    public int maxNumberOfGreenCells;

    private float timeToSpawn;

    void Start()
    {
        timeToSpawn = 1;
        greenCells = new List<GameObject>();
        StartCoroutine("GreenCellSpawner");
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
                    Debug.Log(position);
                    greenCells.Add(cell);
                    cell.transform.SetParent(greenCellsParent.transform);
                    if (greenCells.Count < maxNumberOfGreenCells / 2)
                    {
                        timeToSpawn = Random.Range(0.5f, 2.0f);
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
                Debug.Log("TOOCLOSE!!!" + position);
            }
        }
    }

    bool IsTooClose(Vector3 objectToSpawn, List<GameObject> alreadySpawned)
    {
        foreach (GameObject previousObject in alreadySpawned)
        {
            float distanceX = Mathf.Abs(objectToSpawn.x - previousObject.transform.position.x);
            float distanceY = Mathf.Abs(objectToSpawn.y - previousObject.transform.position.y);

            if (distanceX < 150f && distanceY < 100)
            {
                return true;
            }
        }
        return false;
    }
}
