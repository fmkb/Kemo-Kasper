using UnityEngine;
using UnityEngine.UI;

public class GameManager : MonoBehaviour
{
    public int maxNumberOfGreenCells;
    public float greenCellsSpeed;
    public float chancesForReplication;
    public float replicationTime;

    public int numberOfGreenCells;

    private CellSpawner cellSpawner;

    public GameObject startScreen, secondScreen;

    public Button backButton, continueButton1, continueButton2;

    void Start()
    {
        Time.timeScale = 0;

        backButton.onClick.AddListener(GoBackToMenu);
        continueButton1.onClick.AddListener(CloseFirstScreen);
        continueButton2.onClick.AddListener(CloseSecondScreen);

        cellSpawner = FindObjectOfType<CellSpawner>();

        startScreen.SetActive(true);
        secondScreen.SetActive(false);
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
        Time.timeScale = 1;
    }

    void GoBackToMenu()
    {

    }
}
