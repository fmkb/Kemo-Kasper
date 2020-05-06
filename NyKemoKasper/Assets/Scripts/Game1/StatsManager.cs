using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class StatsManager : MonoBehaviour
{
    public Image healthBar, cellsBar;

    Vector3 defaultPositionHealth;
    Vector3 defaultScaleHealth;
    Vector3 defaultPositionCells;
    Vector3 defaultScaleCells;
    Vector3 defaultPositionCells2;
    Vector3 defaultScaleCells2;

    private int max;
    private int current;

    public RawImage greenCellsBar;

    void Start()
    {
        defaultPositionHealth = healthBar.rectTransform.position;
        defaultScaleHealth = healthBar.rectTransform.localScale;
        defaultPositionCells = cellsBar.rectTransform.position;
        defaultScaleCells = cellsBar.rectTransform.localScale;
        defaultPositionCells2 = greenCellsBar.rectTransform.localPosition;
        defaultScaleCells2 = greenCellsBar.rectTransform.localScale;

        healthBar.rectTransform.localPosition = new Vector3(-140, 1, 1);
        healthBar.rectTransform.localScale = new Vector3(0, 1, 1);

        max = 860;
        current = 0;
    }
    
    void Update()
    {
        
    }

    public void IncreaseStats()
    {
        if (current < max)
        {
            healthBar.transform.localPosition += new Vector3(defaultPositionHealth.x / max / 3, 0, 0);
            healthBar.transform.localScale += new Vector3(defaultScaleHealth.x / max, 0, 0);

            cellsBar.transform.localPosition -= new Vector3(defaultPositionCells.x / max / 3, 0, 0);
            cellsBar.transform.localScale -= new Vector3(defaultScaleCells.x / max, 0, 0);

            current++;
        }
    }

    public void DecreaseStats()
    {
        if (current > 0)
        {
            healthBar.transform.localPosition -= new Vector3(defaultPositionHealth.x / max / 3, 0, 0);
            healthBar.transform.localScale -= new Vector3(defaultScaleHealth.x / max, 0, 0);

            cellsBar.transform.localPosition += new Vector3(defaultPositionCells.x / max / 3, 0, 0);
            cellsBar.transform.localScale += new Vector3(defaultScaleCells.x / max, 0, 0);

            current--;
        }
    }

    public float GetProcentCellsLeft()
    {
        return (float)current / (float)max;
    }

    IEnumerator MoveCellsBar()
    {
        float elapsedTime = 0;

        while (elapsedTime <= 2)
        {
            greenCellsBar.rectTransform.localPosition = Vector3.Lerp(greenCellsBar.rectTransform.localPosition,
            new Vector3((defaultPositionCells2.x - GetProcentCellsLeft() * 460) / 30, defaultPositionCells2.y, defaultPositionCells2.z),
            (Time.deltaTime));
            
            greenCellsBar.rectTransform.localScale = Vector3.Lerp(greenCellsBar.rectTransform.localScale,
            new Vector3(((1 - GetProcentCellsLeft()) * greenCellsBar.rectTransform.localScale.x), defaultScaleCells2.y, defaultScaleCells2.z),
            (Time.deltaTime));

            elapsedTime += Time.deltaTime;

            yield return null;
        }
        yield return null;
    }

    public void CalculateCellsLeft()
    {
        StartCoroutine("MoveCellsBar");
    }
}
