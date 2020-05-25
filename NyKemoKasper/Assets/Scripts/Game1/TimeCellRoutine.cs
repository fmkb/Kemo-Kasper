using UnityEngine;

public class TimeCellRoutine : MonoBehaviour
{
    private GameManager gameManager;
    private bool wasBonusUsed;



    void Start()
    {
        gameManager = FindObjectOfType<GameManager>();
        wasBonusUsed = false;
    }

    // When a time cell enters the pipe
    private void OnTriggerEnter2D(Collider2D other)
    {
        if (other.name == "Pipe")
        {
            if (!wasBonusUsed)
            {
                gameManager.PlayGetBonusSound();
                GetComponent<Animator>().gameObject.SetActive(false);
                GetComponent<Animator>().gameObject.SetActive(true);
                GetComponent<Animator>().Play("OtherCellExplode");

                Destroy(this.gameObject, 1 / 6f);

                gameManager.AddTime();

                wasBonusUsed = true;
            }
        }
    }
}
