using System.Collections;
using UnityEngine;
using UnityEngine.UI;

public class GameManager : MonoBehaviour
{
    public int maxNumberOfGreenCells;
    public int initialNumberOfGreenCells;
    public float greenCellsSpeed;
    public float chancesForReplication;
    public float replicationTime;
    public int roundTime;

    public int timeForBonus;
    public int amountForBonus;

    public int normalPoints;
    public int bonusPoints;

    public int numberOfGreenCells;

    private CellSpawner cellSpawner;
    private ScoreCounter scoreCounter;
    private StatsManager statsManager;

    public GameObject startScreen, secondScreen, pauseScreen, defaultScreen, roundFinished, roundSummary;

    public Button backButton1, continueButton1, continueButton2, pauseButton, restroreButton, backButton2;

    void Start()
    {
        Time.timeScale = 0;

        backButton1.onClick.AddListener(GoBackToMenu);
        backButton2.onClick.AddListener(GoBackToMenu);
        continueButton1.onClick.AddListener(CloseFirstScreen);
        continueButton2.onClick.AddListener(CloseSecondScreen);
        pauseButton.onClick.AddListener(PauseGame);
        restroreButton.onClick.AddListener(RestoreGame);

        cellSpawner = FindObjectOfType<CellSpawner>();
        scoreCounter = FindObjectOfType<ScoreCounter>();
        statsManager = FindObjectOfType<StatsManager>();

        startScreen.SetActive(true);
        secondScreen.SetActive(false);
        defaultScreen.SetActive(false);
        roundFinished.SetActive(false);
        roundSummary.SetActive(false);
    }
    
    void Update()
    {
        numberOfGreenCells = cellSpawner.GetNumberOfGreenCells();
    }

    public void ReplicateCell(Transform origin)
    {
        cellSpawner.ReplicateGreenCell(origin);
    }

    void CloseFirstScreen()
    {
        startScreen.SetActive(false);
        secondScreen.SetActive(true);

    }

    void CloseSecondScreen()
    {
        secondScreen.SetActive(false);
        defaultScreen.SetActive(true);
        Time.timeScale = 1;
    }

    void PauseGame()
    {
        defaultScreen.SetActive(false);
        pauseScreen.SetActive(true);
        Time.timeScale = 0;
    }

    void RestoreGame()
    {
        defaultScreen.SetActive(true);
        pauseScreen.SetActive(false);
        Time.timeScale = 1;
    }

    public void DisplayRoundFinished()
    {
        defaultScreen.SetActive(false);
        roundFinished.SetActive(true);
        StartCoroutine(Counter(2));
    }

    void ShowRoundSummary()
    {
        roundSummary.SetActive(true);
        statsManager.CalculateCellsLeft();
        scoreCounter.GetSummary();
    }

    void GoBackToMenu()
    {

    }

    public bool isGamePaused()
    {
        if(pauseScreen.activeInHierarchy)
        {
            return true;
        }
        return false;
    }

    public IEnumerator Counter(float time)
    {
        cellSpawner.KillAllTheCells();
        yield return new WaitForSeconds(time);
        roundFinished.SetActive(false);
        ShowRoundSummary();
    }

    public void AddTimeStamp()
    {
        scoreCounter.AddTimeStamp();
    }

    public bool CheckForBonus()
    {
        return scoreCounter.CheckForBonus();
    }

    public void IncreaseStats()
    {
        statsManager.IncreaseStats();
    }

    public void DecreaseStats()
    {
        statsManager.DecreaseStats();
    }
}
