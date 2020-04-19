using UnityEngine;

public class GreenCellWalk : MonoBehaviour
{
    private float speed;
    private Rigidbody2D rb;

    private GameManager gameManager;

    void Start()
    {
        rb = GetComponent<Rigidbody2D>();
        gameManager = FindObjectOfType<GameManager>();
        speed = gameManager.greenCellsSpeed;
    }
    
    void Update()
    {
        rb.velocity = new Vector3(0, -speed * 10, 0);

        if(transform.position.y < -630)
        {
            Destroy(this.gameObject);
        }
    }

    public void ZeroSpeed()
    {
        speed = 0;
    }
}
