using System.Collections;
using UnityEngine;
using UnityEngine.UI;

public class StatsManager : MonoBehaviour
{
    private GameManager gameManager;

    public Image healthBar, cellsBar, greenCellsBar, currentChar;

    Vector3 defaultPositionHealth, defaultScaleHealth, defaultPositionCells, defaultScaleCells, 
            defaultPositionCells2, defaultScaleCells2;

    public Sprite[] charAtlas;

    private int max, killedSoFar, defaultCharNo, avatarStageNo;

    private float avatarStageMilestone;



    void Start()
    {
        gameManager = FindObjectOfType<GameManager>();

        defaultPositionHealth = healthBar.rectTransform.position;
        defaultScaleHealth = healthBar.rectTransform.localScale;
        defaultPositionCells = cellsBar.rectTransform.position;
        defaultScaleCells = cellsBar.rectTransform.localScale;
        defaultPositionCells2 = greenCellsBar.rectTransform.localPosition;
        defaultScaleCells2 = greenCellsBar.rectTransform.localScale;

        healthBar.rectTransform.localPosition = new Vector3(-140, 1, 1);
        healthBar.rectTransform.localScale = new Vector3(0, 1, 1);

        max = gameManager.totalGreenCellsToKill;
    }

    // Function for increasing the health and decreasing the green cells bar
    public void IncreaseStats()
    {
        if (killedSoFar < max)
        {
            healthBar.transform.localPosition += new Vector3(defaultPositionHealth.x / max / 3, 0, 0);
            healthBar.transform.localScale += new Vector3(defaultScaleHealth.x / max, 0, 0);

            cellsBar.transform.localPosition -= new Vector3(defaultPositionCells.x / max / 3, 0, 0);
            cellsBar.transform.localScale -= new Vector3(defaultScaleCells.x / max, 0, 0);

            killedSoFar++;
            avatarStageMilestone++;
            CheckForUpperAvatarStage();
        }
    }

    // Function for decreasing the healths and increasing the green cells bar
    public void DecreaseStats()
    {
        if (killedSoFar > 0)
        {
            healthBar.transform.localPosition -= new Vector3(defaultPositionHealth.x / max / 3, 0, 0);
            healthBar.transform.localScale -= new Vector3(defaultScaleHealth.x / max, 0, 0);

            cellsBar.transform.localPosition += new Vector3(defaultPositionCells.x / max / 3, 0, 0);
            cellsBar.transform.localScale += new Vector3(defaultScaleCells.x / max, 0, 0);

            killedSoFar--;
        }
    }

    private void CheckForUpperAvatarStage()
    {
        if(avatarStageMilestone >= (float)max/12)
        {
            avatarStageMilestone = 0;
            avatarStageNo++;

            int spriteNo = 0;

            switch(defaultCharNo)
            {
                case 1: spriteNo = 12; break;
                case 2: spriteNo = 24; break;
                case 3: spriteNo = 0; break;
                case 4: spriteNo = 36; break;
            }

            if(charAtlas.Length >= avatarStageNo - 1 + spriteNo)
            currentChar.sprite = charAtlas[avatarStageNo - 1 + spriteNo];
        }
    }

    // Function for getting a percentage of how many green cells are left
    public float GetProcentCellsKilled()
    {
        return (float)killedSoFar / (float)max;
    }

    // Routine for moving green cells bar on the round summary
    IEnumerator MoveCellsBar()
    {
        float elapsedTime = 0;

        yield return new WaitForSeconds(1);
        while (elapsedTime <= 2)
        {

            greenCellsBar.rectTransform.localPosition = Vector3.Lerp(greenCellsBar.rectTransform.localPosition,
            new Vector3(defaultPositionCells2.x - GetProcentCellsKilled() * 460, defaultPositionCells2.y, defaultPositionCells2.z),
            (2.5f * Time.deltaTime));
            
            greenCellsBar.rectTransform.localScale = Vector3.Lerp(greenCellsBar.rectTransform.localScale,
            new Vector3((1 - GetProcentCellsKilled()) * defaultScaleCells2.x, defaultScaleCells2.y, defaultScaleCells2.z),
            (2.5f * Time.deltaTime));

            elapsedTime += Time.deltaTime;

            yield return null;
        }
        yield return null;
    }

    // Function for calling the routine from the game manager
    public void CalculateCellsLeft()
    {
        StartCoroutine("MoveCellsBar");
    }

    public void RestartStats()
    {
        for(int i = 0; i < max; i++)
        {
            DecreaseStats();
        }

        greenCellsBar.rectTransform.localPosition = defaultPositionCells2;
        greenCellsBar.rectTransform.localScale = defaultScaleCells2;
        
        killedSoFar = 0;
        avatarStageNo = 1;
        avatarStageMilestone = 0;
    }

    public void SetDefaultCharNo(int no)
    {
        defaultCharNo = no;
        int spriteNo = 0;
        switch (defaultCharNo)
        {
            case 1: spriteNo = 12; break;
            case 2: spriteNo = 24; break;
            case 3: spriteNo = 0; break;
            case 4: spriteNo = 36; break;
        }
        currentChar.sprite = charAtlas[0 + spriteNo];
    }
}
