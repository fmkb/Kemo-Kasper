using UnityEngine;

public class GreenCellsClicker : MonoBehaviour
{
    private Collider2D _collider;
    private GameManager gameManager;
    
    void Start()
    {
        _collider = GetComponent<Collider2D>();
        gameManager = FindObjectOfType<GameManager>();
    }

    void Update()
    {
        if (Input.touchCount > 0 && Input.GetTouch(0).phase == TouchPhase.Began)
        {

            var wp = Camera.main.ScreenToWorldPoint(Input.GetTouch(0).position);
            var touchPosition = new Vector2(wp.x, wp.y);

            if (_collider == Physics2D.OverlapPoint(touchPosition))
            {
                DestroyTheCell();
            }
        }
    }

    private void OnMouseDown()
    {
        DestroyTheCell();
    }

    private void DestroyTheCell()
    {
        if (!gameManager.isGamePaused())
        {
            GetComponent<GreenCellRoutine>().ZeroSpeed();
            GetComponent<Animator>().Play("GreenCellDestroy");
            Destroy(this.gameObject, 0.45f);
        }
    }
}
