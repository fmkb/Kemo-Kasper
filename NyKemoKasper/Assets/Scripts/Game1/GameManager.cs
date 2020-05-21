using System.Collections;
using UnityEngine;
using UnityEngine.UI;

public class GameManager : MonoBehaviour
{
    private int lvlNo, noGreenCellsKilledSoFar;

    public int maxNumberOfGreenCells, initialNumberOfGreenCells, roundTime, maxNumberOfOtherCells, timeForBonus,
               amountForBonus, normalPoints, bonusPoints, numberOfGreenCells, totalGreenCellsToKill;

    public float greenCellsSpeed, chancesForReplication, replicationTime;
    
    private CellSpawner cellSpawner;
    private ScoreCounter scoreCounter;
    private StatsManager statsManager;
    private Timer timer;
    private KemoKasperRoutine kemoKasperRoutine;
    private TimeCellRoutine timeCellRoutine;

    public GameObject startScreen, launchScreen1, launchScreen2, launchScreen3, launchScreen4, launchScreenNo, 
        pauseScreen, defaultScreen, roundFinished, roundSummary, gameWonScreen, afterGameScreen, chooseAvatarScreen;

    public Sprite char1, char2, char3, char4;
    public Image currentChar1, currentChar2, currentCharMiniature;

    public GameObject genderBoy, genderGirl, genderBoy1, genderGirl1;

    public Button backButton1, continueButton1, pauseButton, restroreButton, backButton2, continueSummaryButton,
                  replayGameButton, menuButton, avatar1, avatar2, avatar3, avatar4;



    void Start()
    {
        lvlNo = 1;
        noGreenCellsKilledSoFar = 0;

        cellSpawner = FindObjectOfType<CellSpawner>();
        scoreCounter = FindObjectOfType<ScoreCounter>();
        statsManager = FindObjectOfType<StatsManager>();
        kemoKasperRoutine = FindObjectOfType<KemoKasperRoutine>();
        timer = FindObjectOfType<Timer>();
        timeCellRoutine = FindObjectOfType<TimeCellRoutine>();

        Time.timeScale = 0;

        backButton1.onClick.AddListener(GoBackToMenu);
        backButton2.onClick.AddListener(GoBackToMenu);
        menuButton.onClick.AddListener(GoBackToMenu);
        continueButton1.onClick.AddListener(CloseFirstScreen);
        pauseButton.onClick.AddListener(PauseGame);
        restroreButton.onClick.AddListener(RestoreGame);
        continueSummaryButton.onClick.AddListener(LaunchNewLevel);
        replayGameButton.onClick.AddListener(RestartGame);
        avatar1.onClick.AddListener(delegate { ChooseCharacterFromDefault(1); });
        avatar2.onClick.AddListener(delegate { ChooseCharacterFromDefault(2); });
        avatar3.onClick.AddListener(delegate { ChooseCharacterFromDefault(3); });
        avatar4.onClick.AddListener(delegate { ChooseCharacterFromDefault(4); });

        launchScreen1.transform.GetChild(4).GetComponent<Button>().onClick.AddListener(CloseSecondScreen);
        launchScreen2.transform.GetChild(4).GetComponent<Button>().onClick.AddListener(CloseSecondScreen);
        launchScreen3.transform.GetChild(5).GetComponent<Button>().onClick.AddListener(CloseSecondScreen);
        launchScreen4.transform.GetChild(2).GetComponent<Button>().onClick.AddListener(CloseSecondScreen);

        chooseAvatarScreen.SetActive(true);
        startScreen.SetActive(false);
        launchScreen1.SetActive(false);
        launchScreen2.SetActive(false);
        launchScreen3.SetActive(false);
        launchScreen4.SetActive(false);
        defaultScreen.SetActive(false);
        roundFinished.SetActive(false);
        roundSummary.SetActive(false);
    }
    
    // Updating the number of green cells
    void Update()
    {
        numberOfGreenCells = cellSpawner.GetNumberOfGreenCells();
    }

    // Call function for replicating a cell
    public void ReplicateCell(Transform origin)
    {
        cellSpawner.ReplicateGreenCell(origin);
    }

    // Close the first information screen (the one on the first round)
    void CloseFirstScreen()
    {
        startScreen.SetActive(false);
        launchScreen1.SetActive(true);
    }

    // Close the second information screen
    void CloseSecondScreen()
    {
        launchScreen1.SetActive(false);
        launchScreen2.SetActive(false);
        launchScreen3.SetActive(false);
        launchScreen4.SetActive(false);
        defaultScreen.SetActive(true);

        Time.timeScale = 1;

        //cellSpawner.StartSpawningOtherCells();
        //kemoKasperRoutine.TurnOnCallButton(); // TO BE DELETED LATER

        if (lvlNo > 1)
        {
            if (lvlNo < 5)
            {
                replicationTime = 2 / 3f * replicationTime;
                cellSpawner.StartSpawningMoreGreenCells();
            }
            if(lvlNo == 3)
            {
                cellSpawner.StartSpawningOtherCells();
            }

            kemoKasperRoutine.TurnOnCallButton();  // TO BE UNCOMMENTED LATER 
        }
        else
        {
            cellSpawner.StartSpawningMoreGreenCells();
            statsManager.RestartStats();
            scoreCounter.ZeroOutPoints();
        }
        cellSpawner.ReenableSpawning();
        timer.RestartTimer();
        scoreCounter.continueButton.gameObject.SetActive(false);
    }

    // Function for loading the next level
    void LaunchNewLevel()
    {
        roundSummary.SetActive(false);
        if (noGreenCellsKilledSoFar < totalGreenCellsToKill)
        {
            lvlNo++;
            switch (lvlNo)
            {
                case 1:
                    launchScreen1.SetActive(true);
                    initialNumberOfGreenCells = 9;
                    maxNumberOfGreenCells = 30;
                    replicationTime = replicationTime * 4;
                    scoreCounter.ZeroOutPoints();
                    break;
                case 2:
                    launchScreen2.SetActive(true);
                    maxNumberOfGreenCells = 50;
                    initialNumberOfGreenCells = 12;
                    replicationTime = replicationTime / 2;
                    break;
                case 3:
                    launchScreen3.SetActive(true);
                    maxNumberOfGreenCells = 70;
                    initialNumberOfGreenCells = 15;
                    replicationTime = replicationTime / 2;
                    break;
                default:
                    launchScreen4.SetActive(true);
                    maxNumberOfGreenCells = 90;
                    initialNumberOfGreenCells = 18;
                    launchScreenNo.GetComponent<Text>().text = "" + lvlNo;
                    break;
            }
        }
        else
        {
            afterGameScreen.SetActive(true);
        }
    }

    // Function for pausing the game
    void PauseGame()
    {
        defaultScreen.SetActive(false);
        pauseScreen.SetActive(true);
        Time.timeScale = 0;
    }

    // Function for restarting the game when finished
    void RestartGame()
    {
        cellSpawner.StopAllSpawning();
        afterGameScreen.SetActive(false);
        noGreenCellsKilledSoFar = 0;

        lvlNo = 0;
        chooseAvatarScreen.SetActive(true);
    }

    // Function for restoring the game
    void RestoreGame()
    {
        defaultScreen.SetActive(true);
        pauseScreen.SetActive(false);
        Time.timeScale = 1;
    }

    // Function for displaying the first screen after a round is completed
    public void DisplayRoundFinished()
    {
        defaultScreen.SetActive(false);
        roundFinished.SetActive(true);
        StartCoroutine(Counter(2));
    }

    // Function for displaying the second screen after a round is completed (the one with scores etc)
    void ShowRoundSummary()
    {
        roundSummary.SetActive(true);
        statsManager.CalculateCellsLeft();
        scoreCounter.GetSummary();
    }

    // Function for opening the main menu
    void GoBackToMenu()
    {
    }

    // Function for checking if the game is paused
    public bool IsGamePaused()
    {
        return pauseScreen.activeInHierarchy;
    }

    // Function for displaying the end game screens
    private void EndGame()
    {
        defaultScreen.SetActive(false);
        gameWonScreen.SetActive(true);
        timer.StopTimer();
        StartCoroutine(Counter(3));
    }

    // Routine for displaying the first finish screen and the second one after a while
    public IEnumerator Counter(float time)
    {
        cellSpawner.KillAllTheCells();
        kemoKasperRoutine.DisableKasper();

        yield return new WaitForSeconds(time);

        roundFinished.SetActive(false);
        gameWonScreen.SetActive(false);
        ShowRoundSummary();
    }

    // Call function for adding time stamps when a cell is killed
    public void AddTimeStamp()
    {
        scoreCounter.AddTimeStamp();
    }

    // Call function checking if bonus points should be given
    public bool CheckForBonus()
    {
        return scoreCounter.CheckForBonus();
    }

    // Call function increasing the stats
    public void IncreaseStats()
    {
        statsManager.IncreaseStats();
        noGreenCellsKilledSoFar++;
        if(noGreenCellsKilledSoFar >= totalGreenCellsToKill)
        {
            EndGame();
        }
    }

    // Call function decreasing the stats
    public void DecreaseStats()
    {
        statsManager.DecreaseStats();
        noGreenCellsKilledSoFar--;
    }

    // Call function checking how many cells are in a radius
    public int HowManyGreenCellsInPosition(Vector3 position, float radius)
    {
        return cellSpawner.HowManyCellsInRadius(position, radius);
    }

    // Call function killing cells in a radius
    public void DestroyGreenCellsInRadius(Vector3 position, float radius)
    {
        cellSpawner.KillCellsInRadius(position, radius);
    }

    // Call function displaying points for kasper's kills
    public void DisplayKasperPoints(int number)
    {
        kemoKasperRoutine.ShowPoints(number);
        scoreCounter.numberCellsKilledKasper += number;
    }

    // Call function adding bonus points to the total sumS
    public void AddPointsForBonusCell()
    {
        scoreCounter.totalBonusScore += 100;
    }

    // Cell function adding time to the timer
    public void AddTime()
    {
        timer.AddTime();
    }

    void ChooseCharacterFromDefault(int no)
    {
        chooseAvatarScreen.SetActive(false);
        switch(no)
        {
            case 1:
                currentChar1.sprite = char1;
                currentChar2.sprite = char1;
                currentCharMiniature.sprite = char1;
                genderBoy.SetActive(true);
                genderGirl.SetActive(false);
                genderBoy1.SetActive(true);
                genderGirl1.SetActive(false);
                statsManager.SetDefaultCharNo(1);
                break;
            case 2:
                currentChar1.sprite = char2;
                currentChar2.sprite = char2;
                currentCharMiniature.sprite = char2;
                genderBoy.SetActive(false);
                genderGirl.SetActive(true);
                genderBoy1.SetActive(false);
                genderGirl1.SetActive(true);
                statsManager.SetDefaultCharNo(2);
                break;
            case 3:
                currentChar1.sprite = char3;
                currentChar2.sprite = char3;
                currentCharMiniature.sprite = char3;
                genderBoy.SetActive(true);
                genderGirl.SetActive(false);
                genderBoy1.SetActive(true);
                genderGirl1.SetActive(false);
                statsManager.SetDefaultCharNo(3);
                break;
            case 4:
                currentChar1.sprite = char4;
                currentChar2.sprite = char4;
                currentCharMiniature.sprite = char4;
                genderBoy.SetActive(false);
                genderGirl.SetActive(true);
                genderBoy1.SetActive(false);
                genderGirl1.SetActive(true);
                statsManager.SetDefaultCharNo(4);
                break;
        }
        startScreen.SetActive(true);
    }
}
