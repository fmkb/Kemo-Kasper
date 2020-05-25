using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class ButtonScaleOnHover : MonoBehaviour
{
    private Vector3 defaultScale;

    private void Start()
    {
        defaultScale = transform.localScale;
        this.GetComponent<Button>().onClick.AddListener(ScaleDownOnClick);
    }

    public void PointerEnter()
    {
        transform.localScale = defaultScale * 1.2f;
        //StartCoroutine("ScaleButtonUp");
    }

    public void PointerExit()
    {
        //StopCoroutine("ScaleButtonUp");
        transform.localScale = defaultScale;
    }

    private void ScaleDownOnClick()
    {
        PointerExit();
    }

    //private IEnumerator ScaleButtonUp()
    //{
    //    float elapsedTime = 0;
        
    //    Time.timeScale = 1;

    //    while (elapsedTime <= 1)
    //    {
    //        transform.localScale = Vector3.Lerp(transform.localScale,
    //        defaultScale * 1.2f,
    //        Time.deltaTime * 6);

    //        elapsedTime += Time.deltaTime;
    //        yield return null;
    //    }

    //    Time.timeScale = 0;

    //    yield return null;
    //}


}
