using UnityEngine;

public class GameManager : MonoBehaviour
{
    public int maxNumberOfGreenCells;
    public float greenCellsSpeed;
    public float chancesForReplication;
    public float replicationTime;

    public int numberOfGreenCells;

    private CellSpawner cellSpawner;

    void Start()
    {
        cellSpawner = FindObjectOfType<CellSpawner>();
    }
    
    void Update()
    {
        numberOfGreenCells = cellSpawner.GetNumberOfGreenCells();
    }

    public void ReplicateCell(Transform origin)
    {
        cellSpawner.ReplicateGreenCell(origin);
    }
}
