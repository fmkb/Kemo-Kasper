using System.Collections;
using UnityEngine;
using UnityEngine.UI;

public class GameManager : MonoBehaviour
{
    private int lvlNo;

    public int maxNumberOfGreenCells;
    public int initialNumberOfGreenCells;
    public float greenCellsSpeed;
    public float chancesForReplication;
    public float replicationTime;
    public int roundTime;
    public int maxNumberOfOtherCells;

    public int timeForBonus;
    public int amountForBonus;

    public int normalPoints;
    public int bonusPoints;

    public int numberOfGreenCells;

    private CellSpawner cellSpawner;
    private ScoreCounter scoreCounter;
    private StatsManager statsManager;
    private Timer timer;
    private KemoKasperRoutine kemoKasperRoutine;

    public GameObject startScreen, launchScreen1, launchScreen2, launchScreen3, launchScreen4, launchScreenNo, 
        pauseScreen, defaultScreen, roundFinished, roundSummary;

    public Button backButton1, continueButton1, pauseButton, restroreButton, backButton2, continueSummaryButton;

    void Start()
    {
        lvlNo = 1;

        cellSpawner = FindObjectOfType<CellSpawner>();
        scoreCounter = FindObjectOfType<ScoreCounter>();
        statsManager = FindObjectOfType<StatsManager>();
        kemoKasperRoutine = FindObjectOfType<KemoKasperRoutine>();
        timer = FindObjectOfType<Timer>();

        Time.timeScale = 0;

        backButton1.onClick.AddListener(GoBackToMenu);
        backButton2.onClick.AddListener(GoBackToMenu);
        continueButton1.onClick.AddListener(CloseFirstScreen);
        launchScreen1.transform.GetChild(4).GetComponent<Button>().onClick.AddListener(CloseSecondScreen);
        launchScreen2.transform.GetChild(4).GetComponent<Button>().onClick.AddListener(CloseSecondScreen);
        launchScreen3.transform.GetChild(5).GetComponent<Button>().onClick.AddListener(CloseSecondScreen);
        launchScreen4.transform.GetChild(2).GetComponent<Button>().onClick.AddListener(CloseSecondScreen);
        pauseButton.onClick.AddListener(PauseGame);
        restroreButton.onClick.AddListener(RestoreGame);
        continueSummaryButton.onClick.AddListener(LaunchNewLevel);

        startScreen.SetActive(true);
        launchScreen1.SetActive(false);
        launchScreen2.SetActive(false);
        launchScreen3.SetActive(false);
        launchScreen4.SetActive(false);
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
        launchScreen1.SetActive(true);

    }

    void CloseSecondScreen()
    {
        launchScreen1.SetActive(false);
        launchScreen2.SetActive(false);
        launchScreen3.SetActive(false);
        launchScreen4.SetActive(false);
        defaultScreen.SetActive(true);
        Time.timeScale = 1;
        cellSpawner.StartSpawningOtherCells();
        if (lvlNo > 1)
        {
            if (lvlNo < 5)
            {
                replicationTime = 2 / 3f * replicationTime;
                cellSpawner.StartSpawningMoreGreenCells();
            }
            if(lvlNo == 2)
            {
                cellSpawner.StartSpawningOtherCells();
            }
            cellSpawner.ReenableSpawning();
            timer.RestartTimer();
            scoreCounter.continueButton.gameObject.SetActive(false);
        }
        kemoKasperRoutine.TurnOnCallButton();
    }

    void LaunchNewLevel()
    {
        lvlNo++;
        roundSummary.SetActive(false);
        switch(lvlNo)
        {
            case 2: launchScreen2.SetActive(true);
                    maxNumberOfGreenCells = 50;
                    initialNumberOfGreenCells = 12;
                    break;
            case 3: launchScreen3.SetActive(true);
                    maxNumberOfGreenCells = 70;
                    initialNumberOfGreenCells = 15;
                    break;
            default: launchScreen4.SetActive(true);
                    maxNumberOfGreenCells = 90;
                    initialNumberOfGreenCells = 18;
                    launchScreenNo.GetComponent<Text>().text = "" +lvlNo;
                    break;
        }
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
        kemoKasperRoutine.DisableKasper();
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

    public int HowManyGreenCellsInPosition(Vector3 position, float radius)
    {
        return cellSpawner.HowManyCellsInRadius(position, radius);
    }

    public void DestroyGreenCellsInRadius(Vector3 position, float radius)
    {
        cellSpawner.KillCellsInRadius(position, radius);
    }

    public void DisplayKasperPoints(int number)
    {
        kemoKasperRoutine.ShowPoints(number);
        scoreCounter.numberCellsKilledKasper += number;
    }

    public void AddPointsForBonusCell()
    {
        scoreCounter.totalBonusScore += 100;
    }
}
